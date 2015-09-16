/*global define*/
define([
        '../Core/defaultValue',
        '../Core/defined',
        '../Core/DeveloperError',
        '../Shaders/Builtin/CzmBuiltins',
        './AutomaticUniforms'
    ], function(
        defaultValue,
        defined,
        DeveloperError,
        CzmBuiltins,
        AutomaticUniforms) {
    "use strict";

    function removeComments(source) {
        // remove inline comments
        source = source.replace(/\/\/.*/g, '');
        // remove multiline comment block
        return source.replace(/\/\*\*[\s\S]*?\*\//gm, function(match) {
            // preserve the number of lines in the comment block so the line numbers will be correct when debugging shaders
            var numberOfLines = match.match(/\n/gm).length;
            var replacement = '';
            for (var lineNumber = 0; lineNumber < numberOfLines; ++lineNumber) {
                replacement += '\n';
            }
            return replacement;
        });
    }

    function getDependencyNode(name, glslSource, nodes) {
        var dependencyNode;

        // check if already loaded
        for (var i = 0; i < nodes.length; ++i) {
            if (nodes[i].name === name) {
                dependencyNode = nodes[i];
            }
        }

        if (!defined(dependencyNode)) {
            // strip doc comments so we don't accidentally try to determine a dependency for something found
            // in a comment
            glslSource = removeComments(glslSource);

            // create new node
            dependencyNode = {
                name : name,
                glslSource : glslSource,
                dependsOn : [],
                requiredBy : [],
                evaluated : false
            };
            nodes.push(dependencyNode);
        }

        return dependencyNode;
    }

    function generateDependencies(currentNode, dependencyNodes) {
        if (currentNode.evaluated) {
            return;
        }

        currentNode.evaluated = true;

        // identify all dependencies that are referenced from this glsl source code
        var czmMatches = currentNode.glslSource.match(/\bczm_[a-zA-Z0-9_]*/g);
        if (defined(czmMatches) && czmMatches !== null) {
            // remove duplicates
            czmMatches = czmMatches.filter(function(elem, pos) {
                return czmMatches.indexOf(elem) === pos;
            });

            czmMatches.forEach(function(element) {
                if (element !== currentNode.name && ShaderSource._czmBuiltinsAndUniforms.hasOwnProperty(element)) {
                    var referencedNode = getDependencyNode(element, ShaderSource._czmBuiltinsAndUniforms[element], dependencyNodes);
                    currentNode.dependsOn.push(referencedNode);
                    referencedNode.requiredBy.push(currentNode);

                    // recursive call to find any dependencies of the new node
                    generateDependencies(referencedNode, dependencyNodes);
                }
            });
        }
    }

    function sortDependencies(dependencyNodes) {
        var nodesWithoutIncomingEdges = [];
        var allNodes = [];

        while (dependencyNodes.length > 0) {
            var node = dependencyNodes.pop();
            allNodes.push(node);

            if (node.requiredBy.length === 0) {
                nodesWithoutIncomingEdges.push(node);
            }
        }

        while (nodesWithoutIncomingEdges.length > 0) {
            var currentNode = nodesWithoutIncomingEdges.shift();

            dependencyNodes.push(currentNode);

            for (var i = 0; i < currentNode.dependsOn.length; ++i) {
                // remove the edge from the graph
                var referencedNode = currentNode.dependsOn[i];
                var index = referencedNode.requiredBy.indexOf(currentNode);
                referencedNode.requiredBy.splice(index, 1);

                // if referenced node has no more incoming edges, add to list
                if (referencedNode.requiredBy.length === 0) {
                    nodesWithoutIncomingEdges.push(referencedNode);
                }
            }
        }

        // if there are any nodes left with incoming edges, then there was a circular dependency somewhere in the graph
        var badNodes = [];
        for (var j = 0; j < allNodes.length; ++j) {
            if (allNodes[j].requiredBy.length !== 0) {
                badNodes.push(allNodes[j]);
            }
        }

        if (badNodes.length !== 0) {
            var message = 'A circular dependency was found in the following built-in functions/structs/constants: \n';
            for (j = 0; j < badNodes.length; ++j) {
                message = message + badNodes[j].name + '\n';
            }
            throw new DeveloperError(message);
        }
    }

    function getBuiltinsAndAutomaticUniforms(shaderSource) {
        // generate a dependency graph for builtin functions
        var dependencyNodes = [];
        var root = getDependencyNode('main', shaderSource, dependencyNodes);
        generateDependencies(root, dependencyNodes);
        sortDependencies(dependencyNodes);

        // Concatenate the source code for the function dependencies.
        // Iterate in reverse so that dependent items are declared before they are used.
        var builtinsSource = '';
        for (var i = dependencyNodes.length - 1; i >= 0; --i) {
            builtinsSource = builtinsSource + dependencyNodes[i].glslSource + '\n';
        }

        return builtinsSource.replace(root.glslSource, '');
    }

    function combineShader(shaderSource, isFragmentShader) {
        var i;
        var length;

        // Combine shader sources, generally for pseudo-polymorphism, e.g., czm_getMaterial.
        var combinedSources = '';
        var sources = shaderSource.sources;
        if (defined(sources)) {
            for (i = 0, length = sources.length; i < length; ++i) {
                // #line needs to be on its own line.
                combinedSources += '\n#line 0\n' + sources[i];
            }
        }

        combinedSources = removeComments(combinedSources);

        // Extract existing shader version from sources
        var version;
        combinedSources = combinedSources.replace(/#version\s+(.*?)\n/gm, function(match, group1) {
            if (defined(version) && version !== group1) {
                throw new DeveloperError('inconsistent versions found: ' + version + ' and ' + group1);
            }
            // Extract #version to put at the top
            version = group1;

            // Replace original #version directive with a new line so the line numbers
            // are not off by one.  There can be only one #version directive
            // and it must appear at the top of the source, only preceded by
            // whitespace and comments.
            return '\n';
        });

        // Replace main() for picked if desired.
        var pickColorQualifier = shaderSource.pickColorQualifier;
        if (defined(pickColorQualifier)) {
            combinedSources = combinedSources.replace(/void\s+main\s*\(\s*(?:void)?\s*\)/g, 'void czm_old_main()');
            combinedSources += '\
\n' + pickColorQualifier + ' vec4 czm_pickColor;\n\
void main()\n\
{\n\
    czm_old_main();\n\
    if (gl_FragColor.a == 0.0) {\n\
        discard;\n\
    }\n\
    gl_FragColor = czm_pickColor;\n\
}';
        }

        // combine into single string
        var result = '';

        // #version must be first
        // defaults to #version 100 if not specified
        if (defined(version)) {
            result = '#version ' + version;
        }

        if (isFragmentShader) {
            result += '\
#ifdef GL_FRAGMENT_PRECISION_HIGH\n\
    precision highp float;\n\
#else\n\
    precision mediump float;\n\
#endif\n\n';
        }

        // Prepend #defines for uber-shaders
        var defines = shaderSource.defines;
        if (defined(defines)) {
            for (i = 0, length = defines.length; i < length; ++i) {
                var define = defines[i];
                if (define.length !== 0) {
                    result += '#define ' + define + '\n';
                }
            }
        }

        // append built-ins
        if (shaderSource.includeBuiltIns) {
            result += getBuiltinsAndAutomaticUniforms(combinedSources);
        }

        // reset line number
        result += '\n#line 0\n';

        // append actual source
        result += combinedSources;

        return result;
    }

    /**
     * An object containing various inputs that will be combined to form a final GLSL shader string.
     *
     * @param {Object} [options] Object with the following properties:
     * @param {String[]} [options.sources] An array of strings to combine containing GLSL code for the shader.
     * @param {String[]} [options.defines] An array of strings containing GLSL identifiers to <code>#define</code>.
     * @param {String} [options.pickColorQualifier] The GLSL qualifier, <code>uniform</code> or <code>varying</code>, for the input <code>czm_pickColor</code>.  When defined, a pick fragment shader is generated.
     * @param {Boolean} [options.includeBuiltIns=true] If true, referenced built-in functions will be included with the combined shader.  Set to false if this shader will become a source in another shader, to avoid duplicating functions.
     *
     * @exception {DeveloperError} options.pickColorQualifier must be 'uniform' or 'varying'.
     *
     * @example
     * // 1. Prepend #defines to a shader
     * var source = new Cesium.ShaderSource({
     *   defines : ['WHITE'],
     *   sources : ['void main() { \n#ifdef WHITE\n gl_FragColor = vec4(1.0); \n#else\n gl_FragColor = vec4(0.0); \n#endif\n }']
     * });
     *
     * // 2. Modify a fragment shader for picking
     * var source = new Cesium.ShaderSource({
     *   sources : ['void main() { gl_FragColor = vec4(1.0); }'],
     *   pickColorQualifier : 'uniform'
     * });
     *
     * @private
     */
    var ShaderSource = function(options) {
        options = defaultValue(options, defaultValue.EMPTY_OBJECT);
        var pickColorQualifier = options.pickColorQualifier;

        //>>includeStart('debug', pragmas.debug);
        if (defined(pickColorQualifier) && pickColorQualifier !== 'uniform' && pickColorQualifier !== 'varying') {
            throw new DeveloperError('options.pickColorQualifier must be \'uniform\' or \'varying\'.');
        }
        //>>includeEnd('debug');

        this.defines = defined(options.defines) ? options.defines.slice(0) : [];
        this.sources = defined(options.sources) ? options.sources.slice(0) : [];
        this.pickColorQualifier = pickColorQualifier;
        this.includeBuiltIns = defaultValue(options.includeBuiltIns, true);
    };

    ShaderSource.prototype.clone = function() {
        return new ShaderSource({
            sources : this.sources,
            defines : this.defines,
            pickColorQuantifier : this.pickColorQualifier,
            includeBuiltIns : this.includeBuiltIns
        });
    };

    /**
     * Create a single string containing the full, combined vertex shader with all dependencies and defines.
     *
     * @returns {String} The combined shader string.
     */
    ShaderSource.prototype.createCombinedVertexShader = function() {
        return combineShader(this, false);
    };

    /**
     * Create a single string containing the full, combined fragment shader with all dependencies and defines.
     *
     * @returns {String} The combined shader string.
     */
    ShaderSource.prototype.createCombinedFragmentShader = function() {
        return combineShader(this, true);
    };

    /**
     * For ShaderProgram testing
     * @private
     */
    ShaderSource._czmBuiltinsAndUniforms = {};

    // combine automatic uniforms and Cesium built-ins
    for ( var builtinName in CzmBuiltins) {
        if (CzmBuiltins.hasOwnProperty(builtinName)) {
            ShaderSource._czmBuiltinsAndUniforms[builtinName] = CzmBuiltins[builtinName];
        }
    }
    for ( var uniformName in AutomaticUniforms) {
        if (AutomaticUniforms.hasOwnProperty(uniformName)) {
            var uniform = AutomaticUniforms[uniformName];
            if (typeof uniform.getDeclaration === 'function') {
                ShaderSource._czmBuiltinsAndUniforms[uniformName] = uniform.getDeclaration(uniformName);
            }
        }
    }

    return ShaderSource;
});
