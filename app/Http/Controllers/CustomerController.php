<?php

namespace NotiAPP\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use NotiAPP\Http\Requests;
use NotiAPP\Http\Controllers\Controller;

use NotiAPP\Models\Customer;
use NotiAPP\Models\Address;
use NotiAPP\Models\CaseService;
use NotiAPP\Models\Service;
use NotiAPP\Models\Budget;
use NotiAPP\Models\User;

use Auth;

class CustomerController extends Controller
{
     /**
    * Create a new authentication controller instance.
    *
    * @return void
    */
   public function __construct(){
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        //
        $customers;

        if ($request->FullName_write != null) {
                 $customers = Customer::SearchByFullName($request->FullName_write)->orderBy('name','ASC')->get();
            }
            else{
                $customers = Customer::orderBy('name','ASC')->get();
            }

        return view('Customers.CustomerList',['customers'=> $customers]);
    }

  /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function createNew()
    {
        //
       return view('Customers.CreateCustomer');
    } 
    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {   $addCustumer = new Customer;
        $addres = new Address;
        //cuando no provenga de la creacion de un caso, si no de otro modulo, Solo registramos al cliente 
            //no le asignamos un caso. 
            $addCustumer->name = $request->name;
                $addCustumer->fathers_last_name = $request->fathers_last_name;
                $addCustumer->mothers_last_name = $request->mothers_last_name;
                $addCustumer->rfc = $request->rfc;
                $addCustumer->from = $request->from;
                $addCustumer->birthdate = $request->birth_day;
                $addCustumer->occupation = $request->occupation;
                $addCustumer->marital_status = $request->marital_status;
                $addCustumer->phone = $request->phone;
        
                $addCustumer->save();

                //aun esta instanciado a si que tenemos su id 
                $insertedId = $addCustumer->id;

                //instanciamos una direccion 
                $addres->street = $request->street;
                $addres->number = $request->number;
                $addres->colony = $request->colony;
                $addres->postal_code = $request->postal_code;

                //buscamos al cliente para asignarle su direcion y su id de partticipante, caso  
                $customer = Customer::find($insertedId);

                $addres = $customer->address()->save($addres);
                return view('home'); 
    }

    /**
     * Muestra el fomrulario para crear un nuevo ,cliente en un Nuevo Caso
     *
     * @return Response
     */
    public function createNewInNewCase ($id_service)
    {
        //
        return view('Customers.custumerAdd',['id_service'=> $id_service]);
    } 
    
    public function addCustumer(Request $request)
    {
        $addCustumer = new Customer;
        $addres = new Address;

         //El Registro proviene de unar ruta de SelectCustomerForCase > No estaba registrado > Crear uno nuevo > Formulario
         //una ves registrado  le asignamos un caso dependiendo del servicio   
                $addCustumer->name = $request->name;
                $addCustumer->fathers_last_name = $request->fathers_last_name;
                $addCustumer->mothers_last_name = $request->mothers_last_name;
                $addCustumer->rfc = $request->rfc;
                $addCustumer->from = $request->from;
                $addCustumer->birthdate = $request->birth_day;
                $addCustumer->occupation = $request->occupation;
                $addCustumer->marital_status = $request->marital_status;
                $addCustumer->phone = $request->phone;
        
                $addCustumer->save();

                //aun esta instanciado a si que tenemos su id 
                $insertedId = $addCustumer->id;

                //instanciamos una direccion 
                $addres->street = $request->street;
                $addres->number = $request->number;
                $addres->colony = $request->colony;
                $addres->postal_code = $request->postal_code;

                //buscamos al cliente para asignarle su direcion y su id de partticipante, caso  
                $customer = Customer::find($insertedId);

                $addres = $customer->address()->save($addres);
                //Buscamos el servicio del cual se quiere crear un caso, 

                $Service = Service::find($request->id_service);
                //Creamos el caso 
                $NewCase = new CaseService;
                $NewCase->save();
                //identificamos su id 
                $CreateCase = CaseService::find($NewCase->id);
                //Lo relacionamos con el servicio al que peretence
                $Service->case_service()->save($CreateCase);
                //Le Relacionamos el Cliente que Se Registro
                $CreateCase->customer()->attach($customer->id);

                
                //creamos un presupuesto vacio y lo asignamos
                $CaseBudget = new Budget;
                $CaseBudget->save();
                $CaseBudget->case_service()->save($CreateCase);
                
                // obtenemos el usuario que esta logueado y lo asignamos al presupuesto

                    $user = Auth::user();
                    $user->budget()->save($CaseBudget);
                
                //

                return Redirect::route('Show_Case_path', array('id_caseService' => $CreateCase->id ));
         
    }

    /**
     *  Para un caso ya creado, y se quiere asignar clientes existentes
     *
     * @param  Int $id_caseService
     * @return Response
     */
    public function AddCustomersToCase($id_caseService, Request $request){

        $customers;

         if ($request->FullName_write != null) {
                 $customers = Customer::SearchByFullName($request->FullName_write)->orderBy('name','ASC')->get();
            }
            else{
                $customers = Customer::orderBy('name','ASC')->get();
            }

        return view('Customers.CustomersInCase.SelectCustomer_In_Case',[ 'customers' => $customers , 'id_caseService' => $id_caseService ]);
    }

    /**
     *  Para un caso ya creado, y se quiere asignar un lista de clientes
     *
     * @param  Int $id_caseService ,Request $request
     * @return Response
     */
    public function UpdateCustomersInCase($id_caseService, Request $request ){

        /* los estoy expoliendo ya que los es toy pasando en un strign BUSCAR UNA MEJOR MANERA DE ESCOJERLOS*/
        $Sleectedcustomers = explode(',',$request->customers_selected);

        $CreatedCase = CaseService::find($id_caseService);

        //Por cada id de cliente que nos proporcionen asignamos al caso 
        foreach ($Sleectedcustomers as $customerSelect => $idSelectCustomer) {
            
            $Ishere = false;
            //si esque este no aya estado ya asignado 
            foreach ($CreatedCase->customer as $customerInCase) {
                if ($customerInCase->id == $idSelectCustomer ) {
                   $Ishere = true;
                }
            }
            if (!$Ishere) {
              $CreatedCase->customer()->attach($idSelectCustomer);
            }
        }

        $CreatedCase->save();

        //Despues de escojer a los partisipantes, nos dirigimos a los detalles del caso.
        return Redirect::route('Show_Case_path', array('$ServiceCase' => $id_caseService));
    }

     /**
     * Muestra el Formulario para Crear un nuevo cliente y asignarlo
     * @param  Int $id_caseService 
     * @return Response
     */
    public function createNewToCase($id_caseService)
    {
        //
        return view('Customers.CustomersInCase.NewCustomerInCase',['id_caseService' => $id_caseService ] );
    } 
  /**
     * Para un caso ya creado, y se quiere Crear un nuevo cliente y asignarlo
     * @param  Int $id_caseService ,Request $request
     * @return Response
     */
    public function storeNewToCase($id_caseService, Request $request)
    {
        //
         $addCustumer = new Customer;
        $addres = new Address;
        //cuando no provenga de la creacion de un caso, si no que se dece agregar un caso existente, Solo registramos al cliente 
            //no le asignamos un caso. 
            $addCustumer->name = $request->name;
                $addCustumer->fathers_last_name = $request->fathers_last_name;
                $addCustumer->mothers_last_name = $request->mothers_last_name;
                $addCustumer->rfc = $request->rfc;
                $addCustumer->from = $request->from;
                $addCustumer->birthdate = $request->birth_day;
                $addCustumer->occupation = $request->occupation;
                $addCustumer->marital_status = $request->marital_status;
                $addCustumer->phone = $request->phone;
        
                $addCustumer->save();

                //aun esta instanciado a si que tenemos su id 
                $insertedId = $addCustumer->id;

                //instanciamos una direccion 
                $addres->street = $request->street;
                $addres->number = $request->number;
                $addres->colony = $request->colony;
                $addres->postal_code = $request->postal_code;

                //buscamos al cliente para asignarle su direcion y su id de partticipante, caso  
                $customer = Customer::find($insertedId);

                $addres = $customer->address()->save($addres);
         
         $CreatedCase = CaseService::find($id_caseService);

         $CreatedCase->customer()->attach($customer->id);

         return Redirect::route('Show_Case_path', array('id_caseService' => $id_caseService));

    } 



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
        $customer = Customer::find($id);
        $cases_services = $customer->case_service;

       return view('Customers.CustomersInCase.CustomerCasesServices',[ 'customer' => $customer, 'cases_services' => $cases_services]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
        $customer = Customer::find($id);
       return view('Customers.edit',[ 'customer' => $customer, 'address'=> $customer->address[0]]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
        $customer = Customer::find($id);

        $customer->name = $request->name;
        $customer->fathers_last_name = $request->fathers_last_name;
        $customer->mothers_last_name = $request->mothers_last_name;
        $customer->rfc = $request->rfc;
        $customer->from = $request->from;
        $customer->birthdate = $request->birth_day;
        $customer->occupation = $request->occupation;
        $customer->marital_status = $request->marital_status;
        $customer->phone = $request->phone;
        $customer->save();

        //como solo mostramos una direccion solo usamos la primera del arreglo        
        $addres = Address::find($customer->address[0]->id);
        $addres->street = $request->street;
        $addres->number = $request->number;
        $addres->colony = $request->colony;
        $addres->postal_code = $request->postal_code;
        $addres->save();

         return Redirect::route('Customer_Show_path', array('id_customer' => $customer->id));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
