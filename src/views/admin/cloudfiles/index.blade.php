@extends('pixel-admin::layouts.admin')
@section('content')
	
	<div class="content container-fluid">
		<admin-cloudfiles-index></admin-cloudfiles-index>		
		
	</div>
	
</div>

@stop

@section('style')
<style>
#app {
  width: 95%;
  margin: 0 auto;
}

.VuePagination {
  text-align: center;
}

.vue-title {
  text-align: center;
  margin-bottom: 10px;
}

.vue-pagination-ad {
  text-align: center;
}

.glyphicon.glyphicon-eye-open {
  width: 16px;
  display: block;
  margin: 0 auto;
}

th:nth-child(3) {
  text-align: center;
}

.VueTables__child-row-toggler {
  width: 16px;
  height: 16px;
  line-height: 16px;
  display: block;
  margin: auto;
  text-align: center;
}

.VueTables__child-row-toggler--closed::before {
  content: "+";
}

.VueTables__child-row-toggler--open::before {
  content: "-";
}

</style>
@stop

@section('scripts')


@stop