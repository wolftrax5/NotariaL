 @extends('layouts.homedefault')

@section('content') 

<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script>
 

</script>
<div class="block_container">

  <h1>Presupuesto de Escritura Nº {{ $Budget->case_service->id}}</h1>
  <h1>{{ $Budget->case_service->service->name}}</h1>

 <div class="form_container"> 
 <form id="Edit_budget" action="{{route('UpdateBudget',$Budget->id) }}" method='post' class="form_data aling_block">  
   {{csrf_field()}}

  
    <!-- Inpust que se Quitaran dependiendo del servicio -->
 
      @yield('SpecialInputs')

  <!-- Inputs por default de un presupesto -->
      
      <label for="discount">Descuento de Honorarios</label> 
      <input name="discount"  class="input long" id="discount" type="text" autocomplete="off" value="{{$Budget->discount}}" />
      
      <label for="travel_expenses">Gastos de Viaje</label> 
      <input name="travel_expenses"  class="input long" id="travel_expenses" type="text" autocomplete="off" value="{{$Budget->travel_expenses}}" />
      
      <label for="miscellaneous_expense">Varios</label> 
      <input name="miscellaneous_expense"  class="input long" id="miscellaneous_expense" type="text" autocomplete="off" value="{{$Budget->miscellaneous_expense}}" />
      
      <label for="advance_payment">Anticipo</label> 
      <input name="advance_payment" class="input long" id="advance_payment" type="text" autocomplete="off" value="{{$Budget->advance_payment}}" />

      <label for="surcharges">Recargos</label> 
      <input name="surcharges" class="input long" id="surcharges" type="text" autocomplete="off" value="{{$Budget->surcharges}}" />
     
<!-- Inputs por default de un presupesto -->
      <label for="payment_type">Forma de Pago </label>
      <select id="payment_type" name="payment_type" form="Edit_budget" >
        <option value="1" @if($Budget->payment_type == 'efectivo' ){{ "selected" }} @endif >Efectivo</option>
        <option value="2" @if($Budget->payment_type == 'transferencia' ){{ "selected" }} @endif>Transferencia</option>
        <option value="3" @if($Budget->payment_type == 'cheque' ) {{ "selected" }} @endif >Cheque</option>
      </select>

      <label for="approved">Aprobado</label>
      <select id="approved" name="approved" form="Edit_budget" >
        <option value="0"  @if($Budget->approved == 0 ){{ "selected" }}@endif >NO</option>
        <option value="1"  @if($Budget->approved  == 1 ){{ "selected" }}@endif >SI</option>
      </select>

      <label for="invoiced">Facturado</label>
      <select id="invoiced" onchange="sText()" name="invoiced" form="Edit_budget"  >
        <option value="0" @if($Budget->invoiced  == 0 ){{ "selected" }}@endif >NO</option>
        <option value="1" @if($Budget->invoiced  == 1 ){{ "selected" }}@endif >SI</option>
      </select>


      <input type="submit" value="Guardar" class="input budget-button">
      <a class="input budget-button" href="{{route('Show_Case_path',$Budget->case_service->id) }}"> Cancelar </a>

    </form>
  </div> 
 </div> 


 @stop