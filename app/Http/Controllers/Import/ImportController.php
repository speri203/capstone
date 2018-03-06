<?php namespace NGAFID\Http\Controllers\Import;


use NGAFID\Http\Controllers\Controller;
use Request;
use NGAFID\Aircraft;
use NGAFID\Http\Requests\UploadRequest;
use NGAFID\FileUpload;
use NGAFID\Commands\ProcessImportCommand;

class ImportController extends Controller {

    public $perPage = 20;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}


    public function index()
    {
        return redirect('import/status');
    }

    public function store(UploadRequest $uploadRequest)
    {
        $formfields     = $uploadRequest->all();
        $fleetID        = \Auth::user()->org_id;
        $userID         = \Auth::user()->id;
        $fileName       = $formfields['flight_data']->getClientOriginalName();
        $path           = base_path() . '/public/uploads/' . $userID . '/';
        $date           = new \DateTime;
        $date           = date_format($date,"Y-m-d H:i:s");
        $uploadsTable   = new FileUpload();

        if($formfields['flight_data']->move($path, $fileName))
        {
            $uploadsTable->file_name = $fileName;
            $uploadsTable->path = $path;
            $uploadsTable->user_id = $userID;
            $uploadsTable->upload_time = $date;
            $uploadsTable->n_number = $formfields['n_number'];
            $uploadsTable->fleet_id = $fleetID;
            $uploadsTable->aircraft_type = $formfields['aircraft'];
            $uploadsTable->is_submitted = 1;
            $uploadsTable->save();

            $uploadID = $uploadsTable->id;
            \Queue::pushOn('webImport', new ProcessImportCommand($uploadID)); //it seems this processes the file synchronously

            chmod($path, 0777);

            flash()->success('Your flight has been submitted!');
            return redirect('import/upload');
        }

        //\DB::insert('insert into fdmdm.uploaded_file (file_name, user_id, upload_time, n_number, fleet_id, aircraft_type, is_submitted) values (?, ?, ?, ?, ?, ?, ?)', ["{$fileName}", $userID, "{$date}", "{$formfields['n_number']}", $fleetID, $formfields['aircraft'], 0]);
        flash()->success('Oops! There was a problem submitting your flight!');
        return redirect('import');
    }

    public function upload()
    {
        // Only show C172S, C182, Piper Seminole PA44, Piper Archer PA28, Cirrus SR20
        $aircraftTable = new Aircraft();
        $aircraftInfo = $aircraftTable->whereIn('id', [1, 2, 6, 7, 8, 192])->orderBy('aircraft name', 'ASC')->get();  // Only show C172, C182, PA44, PA28, SR20

        $aircraftData[''] = 'Select Aircraft';
        foreach($aircraftInfo as $aircraft)
        {
            $aircraftData[$aircraft['id']] = ($aircraft['aircraft name'] ? $aircraft['aircraft name'] :  $aircraft['make'] . ' ' . $aircraft['model']);
        }

        $data['aircraft'] = $aircraftData;
        return view('import.upload')->with('data', $data);
    }

	public function status()
    {
        $fleetID        = \Auth::user()->org_id;
        $userID         = \Auth::user()->id;

        $importedFlights = FileUpload::importStatus($userID, $fleetID)->paginate($this->perPage);
        return view('import.status')->with(['data' => $importedFlights]);
    }

}
