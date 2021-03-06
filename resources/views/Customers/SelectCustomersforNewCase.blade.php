@extends('layouts.homedefault')

@section('content')
<!-- Parametros: Array Customer, $service->id as $id_service, -->
	
	<div class="block_container">
		<section class="title_continer">
			<img class="title_icon" src="{{ asset('img/icons/system/selectcustomer.ico') }}" alt="">
			<h2 class="title">Seleccionar Cliente para un Nuevo Tramite</h2>
		</section >

			<section class = "action_buttons">

				  <a class="budget-button button_normal" href="{{route('New_Customer_path',$id_service) }}"> 
					<img class="title_icon" src="{{ asset('img/icons/system/registrocliente.ico') }}" alt="Nuevo Cliete">
				  	<p>Nuevo Cliente</p> 
				  </a>
				  <button id="new_case_service" name="customers" type="submit" onClick="newCase()" class="budget-button button_normal"> 
					<img class="title_icon" src="{{ asset('img/icons/system/nuevotramite.ico') }}" alt="Nuevo Tramite">
						<p> Crear Tramite </p>
				  </button>
				  <a class="budget-button button_normal" href="{{route('home') }}">
					<img class="title_icon" src="{{ asset('img/icons/system/cancel.ico') }}" alt="Cancelar">
				  	<p> Cancelar </p>
				  </a>
				  
			</section>
			<table id="customers_Table" class="table-fill">
				<thead>
					<tr>

						<th class="text-center">Selcted</th>
						<th class="text-center">Id</th>
						<th class="text-center th_medium">Nombre
								<!-- Buscador por Nombre Completo -->
							{!! Form::open(array('route' => array('Select_Customers_toCase', $id_service) ,'method' => 'Get','class' => 'form_search')) !!}

								{!! Form::text('FullName_write',null,['class' => 'form_input_search th_medium' ,'placeholder' => 'Nombre o Apellidos' ]) !!}
						
							{!! Form::close() !!}
						</th>
						<th class="text-center">RFC</th>
					</tr>
				</thead>
				<tbody id="body_table" class="table-hover">
				
				<form  id="select_customers" method='POST' >
				 {{csrf_field()}}

					@foreach ($customers as $customer)
    					<tr>
    						<td class="text-center" ><input name="select" type="checkbox" value="{{ $customer->id }}"></td>
    						<td name="id" class="text-center"> {{ $customer->id }} </td>
    						<td class="text-center"> {{ $customer->name .' '.$customer->fathers_last_name }} </td>
    						<td class="text-center"> {{ $customer->rfc }} </td>
    					</tr>
					@endforeach
					
					 <input id="customers_selected" name="customers_selected" type="hidden" value="">
				  
				</form>
				</tbody>
				</table>
	</div>
<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script>



function newCase(){
	var id_service = "<?php echo $id_service; ?>" ;

	var customers_selected = new Array();

	$("input:checkbox[name=select]:checked").each(function(){
    	customers_selected.push($(this).val()); 	
	});

	document.getElementById("customers_selected").value = customers_selected; 

	document.getElementById("select_customers").action = "{{route('crearCaso',$id_service) }}";

	document.getElementById("select_customers").submit(); 
}



	
</script>


@stop