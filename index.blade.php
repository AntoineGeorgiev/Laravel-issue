<?php /*<!--
		File:	resources\views\powr_bo\registered\index.blade.php
		 Ver:	1.00.000
 Purpose:	Registered content for POWR Backoffice index blade
Author/s:	Christopher Georgiev
 Created:	2019-10-07
	Modify:	2019-10-26
-->*/?>



@extends('powr.form')

@section('navigation')
	@include('powr.nav')
@endsection


@section('contents')
<form action="{{route('powr.extendperiod.store')}}" method="POST" enctype="multipart/form-data">
	@csrf

	<input hidden name="applicant_id" value="{{$person_id}}">
	<input hidden name="recruitmentagency_id" value="{{$companyID}}">

	<div class="container-fluid clearfix">
		<div class="tabs">
			<ul class="nav-x clearfix">
				<li><a href="{{route('powr.changerequests.index')}}"><span class="inner">Изменение и допълнение на обстоятелствата</span></a></li>
				<li class="active"><a href="{{route('powr.extendperiod.index')}}"><span class="inner">Продължаване срока на регистрация</span></a></li>
				<li><a href="{{route('powr.endreg.index')}}"><span class="inner">Прекратяване на регистрация</span></a></li>
				<li><a href="{{route('powr.yearly.index')}}"><span class="inner">Подаване на ежегодни документи на ПОВР</span></a></li>
			</ul>
		</div>

		<div class="row">
			<div class="col col-md-10">
				<div class="container-fluid clearfix">
					<div class="content">
						<!-- <h2 class="h3 main-heading">Списък регистрации</h2>-->
						<h2 class="h3 main-heading">Подаване на искане за удължаване на валидност:</h2>

						<div class="form-items">
							<section>
								<!--<h3><span class="primary-bgr">Списък регистрации</span></h3>-->
								<h3><span class="primary-bgr">Искания за удължаване срока на валидност:</span></h3>
								<table class="table">
									<thead>
										<tr>
											<th scope="col">Номер</th>
											<th scope="col">ЕИК/БУЛСТАТ/Идентификатор</th>
											<th scope="col">Наименование</th>
											<th scope="col">Удостоверение за регистрация №/дата</th>
											<th scope="col">Валидност на регистрацията</th>
											<th scope="col">Статус</th>
											<th scope="col">Последна промяна</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($changerequest as $request)
										<tr>
											<td>
												{{ $request->id }}
											</td>
											<td>
												@if ($request->getCompany()->type_id == 535)
												{{$request->getCompany()->foreigncountryidentifier ?? ''}}
												@else
													{{$request->getCompany()->uic ?? ''}}                      
												@endif
											</td>
											<td>{{$request->getCompany()->name ?? ''}}</td>
											<td>
												{{$request->getRegistration()->certificatedecimal}} / {{$request->getRegistration()->certificatedate}}
											</td>
											<td>{{ date('d-m-Y', strtotime($request->getRegistration()->certificatevalidity ?? '')) }}</td>
											<td>{{$request->status ?? ''}}</td>
											<td>{{ date('d-m-Y / H:i:s', strtotime($request->lastupdated ?? '')) }}</td>
										</tr>
										@endforeach
			
									</tbody>
								</table>
							</section>
							<section>
								<!--<h3><span class="primary-bgr">Списък регистрации</span></h3>-->
								<h3><span class="primary-bgr">Подаване на искане за удължаване на валидност:</span></h3>

								<h6><strong>Прикачени файлове</strong></h6>
							
								<table class="table" id="files" name="files">
									<thead>
										<tr>
											<th scope="col">Вид документ</th>
											<th scope="col">Пояснения</th>
											<th scope="col">Име на файл</th>
											<th scope="col"></th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							
								<p><a data-fancybox data-src="#add-file" href="javascript:void(0);" class="btn btn-primary" id="add-new-file">Добави</a></p>
							
								<div class="form-items">

									<th scope="col">Дата:</th>
		
									<div class="gj-clear-both"></div>
									<div class="gj-margin-top-10">
										<input name="date" id="datepicker" width="276" />
									</div>

									<br><br>
		
									<p class="text-center"><button type="submit" class="btn btn-primary btn-lg"> Подай</button> <span class="sep-line "> | </span>  <a href="/" class="btn btn-outline-danger btn-lg">Отказ</a></p>
							</section>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>

<div id="add-file" title="Файл" class="modal" tabindex="-1" role="dialog" style="display: none" enctype="multipart/form-data">
	<section>
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Modal title</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" data-role="close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<div class="form-horizontal" role="form" enctype="multipart/form-data">
						<input type="button" id="loadFileXml" value="Избери файл" onclick="document.getElementById('fileNameM').click();" />
						<input type="file" hidden id="fileNameM" name="fileName"/>
						<label for="fileName" id="lblFile">  Няма въведен файл</label>
						<small id="fileHelp" class="form-text text-muted">Моля, добавете валиден файл. Размерът на файла не може да е повече от 2MB.</small>
					</div>
				</div>
				<div class="form-group">
					<label for="document_type">Вид документ:</label>
					<select name="document_type" id="document_type" class="form-control">
						<option selected value="0">Моля, изберете</option>
						@foreach ($doc_types as $type)
							<option value="{{ $type->id }}">
								{{ $type->description }}
							</option>
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="explanations">Пояснения:</label>
					<input type="text" class="form-control" id="explanations" name="explanations" autocomplete="off">
				</div>
			</div>
			<div class="modal-footer">
				<div data-role="footer">
					<button class="btn btn-primary" type="submit" id="btnOKFile">Добави</button>
					<button class="btn btn-danger" data-role="close" id="closeModal">Изход</button>
				</div>
			</div>
		</div>
	</section>
</div>


<script src="{{asset('js/filesClass.js')}}"></script>
<script src="{{asset('js/powrExtend/addFile.js')}}"></script>
@endsection

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="{{asset('js/datePicker.js')}}"></script>
