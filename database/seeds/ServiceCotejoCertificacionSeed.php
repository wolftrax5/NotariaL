<?php

use Illuminate\Database\Seeder;

use NotiAPP\Models\Service;
use NotiAPP\Models\Document;
use NotiAPP\Models\ParticipantType;
use NotiAPP\Models\Expense;


class ServiceCotejoCertificacionSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
            //
        $service = new Service;

      /* en DatabaseSeeder.php se ejecuta primero el seeder del Catalogo de Documentos
          los buscamos para no generar duplisidad y lo vinculamos 
         */  

        $DocumentosOriginalesID = Document::where('document_name', 'Documentos Originales')->get();
         /* Los buscamos para obtener un modelo Eloquent para poder relacionalros  */
        $DocumentosOriginales= Document::find($DocumentosOriginalesID[0]->id); 

        $CopiaDocumentosID = Document::where('document_name', 'Copia de Documentos' )->get();
        $CopiaDocumentos = Document::find($CopiaDocumentosID[0]->id); 

        /*Obtenemos el tipo de participante que coresponde a este servicio */
        $SolicitanteType = ParticipantType::where('name','Solicitante')->get(); 


        /*Obtenemos los Cobros a considear para el Servicio*/
        $Honorarios = Expense::where('expense_name','Honorarios')->first();
        $HhojaExtra = Expense::where('expense_name','Honorarios Por Hoja Extra')->first();
        $HhojaExtraN = Expense::where('expense_name','Nº Hojas Extra')->first();

        /* Asignamos los datos para Crear el Servicio*/

         $service->name = 'Cotejo y Certificación';
         $service->service_type = 2;
         $service->icon_path = 'img/icons/services/cotejo_y_Certificacion.ico';  
         $service->save();

         $serviceId = $service->id;
         /* Una ves Registrado lo buscamos para hacer las viculaciones */
         $serviceFind = Service::find($serviceId);

        //El costo de honorarios es de 100 Por la primera HOJA para este servicio 
        $serviceFind->expenses()->attach( $Honorarios->id,['cost' => '100', 'input_name' => 'honorarios' ,'type_input' => 'hidden' ] );
        //A Partir de 2 hojas se cobran 30 extra de honoraros
        $serviceFind->expenses()->attach($HhojaExtra->id,['cost' => '30','input_name' => 'honorarios_HojaExtra','type_input' => 'checkbox'] );
        $serviceFind->expenses()->attach($HhojaExtraN->id,['cost' => '0','input_name' => 'nhonorarios_HojaExtra','type_input' => 'number'] );

        $serviceFind->participant_type_service()->attach($SolicitanteType[0]->id );

        $DocumentosOriginales= $serviceFind->document_service()->save($DocumentosOriginales,['participants_type' => 'Solicitante']);
        $CopiaDocumentos = $serviceFind->document_service()->save($CopiaDocumentos,['participants_type' => 'Solicitante']);
        

    }
}
