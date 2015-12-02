@extends('layouts.homedefault')

@section('content')
<!-- Mostrara Todos los Casos Activos del servicio-Post  obtenido 
	Parametros: Objeto Service encotrado por id, Array CaseService filtrado por atributo service_id,
-->
	<div class="block_container">

		<h1>{{ $service->name }}</h1>
			<table class="table-fill">
				<thead>
					<tr>
						<th class="text-left"> 
							<p>Nº Folio</p> 
						<!-- Buscador por Folio -->
						{!! Form::open(array('route' =>array('service_show_path',$service->id ),'method' => 'Get','class' => 'form_search')) !!}
							<div class="navbar_seach">
								{!! Form::number('id',null,['class' => 'form_input_search' ,'placeholder' => 'Id' ]) !!}
							</div>
						{!! Form::close() !!}
						</th>
						<th class="text-left"> <p> Nº Escritura</p> 
						<!-- Buscador por Numero de Escritura -->
						{!! Form::open(array('route' =>array('service_show_path',$service->id ),'method' => 'Get','class' => 'form_search')) !!}
							<div class="navbar_seach">
								{!! Form::number('N_write',null,['class' => 'form_input_search' ,'placeholder' => 'Nº Escritura' ]) !!}
							</div>
						{!! Form::close() !!}
						</th>
						<th class="text-left">Avance</th>
						<th class="text-left">Cliente</th>
						<th class="text-left">Total de Operación</th>
						<th class="text-left">Observaciones</th>
						<th class="text-left">Opcciones</th>

					</tr>
				</thead>
				<tbody class="table-hover">
					
					@foreach ($cases_services as $case_service)
					<a href="{{url('servicio/'.$service->id.'/caso/'.$case_service->id)}}">
    					<tr>
    						<td class="text-center"> {{ $case_service->id }} </td>
                			<td class="text-center"> {{ $case_service->N_write}} </td>
    						<td class="text-center"> {{ $case_service->progress }} %</td>
							<td class="text-center"> 
								@foreach($case_service->customer->all() as $customerCase )
    								{{ $customerCase->name." ".$customerCase->fathers_last_name." ".$customerCase->mothers_last_name }} 
    								<br>
								@endforeach
							</td>
    						<td class="text-center"> ${{ $case_service->budget->total }} </td>
    						<td class="text-center"> {{ $case_service->observations }} </td>
    						<td class="text-center"> <a class="input budget-button button_normal" href="{{route('Show_Case_path',$case_service->id) }}"> Detalles </a></td>
    					</tr>
    				</a>
					@endforeach
				</tbody>
				</table>

			<section class = "action_buttons">
			
				  <a class="input budget-button button_normal" href="{{route('Select_Customers_toCase',$service->id) }}">Nuevo Tramite</a>
				  
			</section>
			</div>

<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script>
	
</script>

@stop
