<div class="ms-2 p-1 px-3 bg-white shadow-sm" style="border-bottom-left-radius: 10px">
	<span class="fs-4 fw-lighter">{{ $title }}</span>
</div>
<div class="d-flex flex-fill flex-column shadow-sm bg-white overflow-auto ms-2 mt-1" style="border-top-left-radius: 10px; border-bottom-left-radius: 10px; border-top: 1px solid #dddddd; border-left: 1px solid #dddddd; border-bottom: 1px solid #dddddd;">
	<div class="flex-shrink-1">
		<div class="d-flex border-bottom">
			<div class="d-flex flex-grow-1 p-2">
				<form class="d-flex flex-grow-1">
					<input type="hidden" name="refid" value="{{ @$_REQUEST['refid'] }}" />
					<select name="total" class="form-select small bg-white rounded-0" style="width: 125px">
						<option value="15" @if((int)$total === 15) selected @endif>15 Baris</option>
						<option value="25" @if((int)$total === 25) selected @endif>25 Baris</option>
						<option value="50" @if((int)$total === 50) selected @endif>50 Baris</option>
					</select>
					<div class="flex-grow-1 input-group ms-2 d-none d-sm-flex">
						<input name="q" type="text" class="form-control bg-white rounded-0" value="{{ @$_REQUEST['q'] }}" placeholder="Descriptions">
						<div class="input-group-append">
							<div class="btn-group h-100">
								<button class="btn btn-outline-primary rounded-0" type="submit" title="Go Search" style="padding: 2px 5px"><i class="material-icons-outlined" style="vertical-align: middle;">search</i></button>
								<a class="btn btn-outline-secondary rounded-0 d-flex align-items-center" id="filter_button" role="button" title="Search options" style="padding: 2px 5px" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample"><i class="material-icons-outlined" style="vertical-align: middle;">filter_alt</i></a>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="p-2">
				<a onclick="event.preventDefault(); globalFunction.loadContent(this)" data-attr="{{ route('master-data-detail.create', ['refid'=>@$_REQUEST['refid']]) }}" role="button" class="d-flex btn btn-warning bg-gradient rounded-0">
					<i class="material-icons-outlined align-middle align-self-center">add</i>
					<span class="px-2 d-none d-sm-flex text-nowrap align-self-center">Tambah</span>
				</a>
			</div>
		</div>
	</div>
	<div class="d-flex flex-fill flex-column p-0 px-3" style="overflow-x: auto!important;">
		<table class="table table-striped table-hover w-100" cellpadding="0" cellspacing="0">
			<thead>
				<tr class="text-nowrap">
					<th width="30px">No</th>
					<th width="150px">Referensi</th>
					<th width="30px">Sequence</th>
					<th width="200px">Shortname</th>
					<th>Descriptions</th>
					<th width="50px">Status</th>
					<th width="50px">Aksi</th>
				</tr>
			</thead>
			<tbody>
				@if($data->isEmpty())
					<tr>
						<td align="center">*</td>
						<td colspan="6">Tidak ada data</td>
					</tr>
				@else
					@php $_i=$data->firstItem(); @endphp
					@foreach($data as $_data)
						<tr @if($_i % 2===0) class="bg-light" @else class="bg-white" @endif valign="top">
							<td align="center">{{ $_i }}</td>
							<td>{{ $_data->masterData->description }}</td>
							<td align="right">{{ $_data->sequence }}</td>
							<td>{{ $_data->shortname ?? '-' }}</td>
							<td>{{ $_data->description ?? '-' }}</td>
							<td>
								@if(\Auth::user()->hasPermission('Master Data', 'suspend'))
									@if($_data->status==='0')
										<a href="{{ route('master-data-detail.status', $_data->id) }}" role="button" class="btn btn-sm btn-info bg-gradient m-0 p-0 ps-2 pe-2" title="Click for suspend">ACTIVE</a>
									@elseif($_data->status==='1')
										<a href="{{ route('master-data-detail.status', $_data->id) }}" role="button" class="btn btn-sm btn-warning m-0 p-0 ps-2 pe-2" title="Click for activate">SUSPEND</a>
									@elseif($_data->status==='3')
										<a href="{{ route('master-data-detail.status', $_data->id) }}" role="button" class="btn btn-sm btn-secondary m-0 p-0 ps-2 pe-2" title="Click for activate">DRAFT</a>
									@endif
								@endif
							</td>
							<td class="text-nowrap">
								@if(is_null($_data->deleted_at))
									@if(\Auth::user()->hasPermission('Master Data', 'update'))
										<a onclick="event.preventDefault(); globalFunction.loadContent(this)" data-attr="{{ route('master-data-detail.edit', $_data) }}" class="btn btn-outline-primary btn-sm p-0 px-2" title="Edit" role="button"><i class="material-icons-outlined p-1 d-flex fs-6">create</i></a>
									@endif
									@if(\Auth::user()->hasPermission('Master Data', 'delete'))
										<form method="post" onsubmit="return confirm('Are you sure want to delete this record?')" action="{{ route('master-data-detail.destroy', $_data) }}" class="d-inline">
											@csrf()
											@method('delete')
											<button type="submit" class="btn btn-outline-danger btn-sm ms-1 p-0 px-2" title="Delete" role="button">
												<i class="material-icons-outlined p-1 d-flex fs-6">clear</i>
											</button>
										</form>
									@endif
								@else
									<a onclick="return confirm('Are you sure want to restore this record?')" href="{{ route('master-data-detail.restore', $_data) }}" class="btn btn-outline-outline-danger btn-sm p-0 px-2" title="Restore" role="button"><i class="material-icons-outlined p-1 d-flex fs-6">restore_page</i></a>
								@endif
							</td>
						</tr>
						@php $_i++; @endphp
					@endforeach
				@endif
			</tbody>
		</table>
	</div>
	<div class="flex-shrink-1">
		<div class="p-2 px-3 d-flex flex-row flex-fill border-top bg-light">
			<div class="d-md-inline d-none small flex-grow-1">Menampilkan {{ (int)$data->firstItem().' sampai '.(int)$data->lastItem() }} dari {{ $data->total().' baris' }}</div>
			<div>{!! str_replace('pagination', 'pagination pagination-sm no-gap mb-0 place-right', $data->onEachSide(1)->render('pagination::bootstrap-4')) !!}</div>
		</div>
	</div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
	<div class="offcanvas-header">
		<h5 class="offcanvas-title" id="offcanvasExampleLabel">Search Options</h5>
		<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
	</div>
	<div class="offcanvas-body">
		<form>
			<div class="row">
				<div class="col-sm-4 small">Tampilkan</div>
				<div class="col-sm-8">
					<select class="form-select rounded-0 bg-white" name="total">
						<option value="25" @if((int)$total === 25) selected @endif>25</option>
						<option value="50" @if((int)$total === 50) selected @endif>50</option>
						<option value="100" @if((int)$total === 100) selected @endif>100</option>
					</select>
				</div>
			</div>
			<div class="row mt-1">
				<div class="col-sm-4 small">Referensi</div>
				<div class="col-sm-8">
					<select class="form-select rounded-0 bg-white" name="refid">
						@foreach($referensi as $_referensi)
							<option value="{{ $_referensi->id }}" @if((int)@$_REQUEST['refid']===(int)$_referensi->id) selected @endif>{{ $_referensi->description }}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="row mt-1">
				<div class="col-sm-4 small">Deskripsi</div>
				<div class="col-sm-8"><input type="text" class="form-control rounded-0 bg-white" name="q" placeholder="Deskripsi data" value="{{ @$_REQUEST['q'] }}" /></div>
			</div>
			<hr />
			<button class="btn btn-outline-primary rounded-0">Lakukan Pencarian</button>
		</form>
	</div>
</div>

@include('backend.partials.form', [
	'modal_type'	=> 'modal-lg',
	'title'			=> $title
])

<script type="module">
	var myModalEl = document.getElementById('appForm');
	myModalEl.addEventListener('hidden.bs.modal', function (event) {
		$('#appForm .modal-body').html('');
	});

	const myModalEl1 = document.getElementById('appForm');
	myModalEl1.addEventListener('shown.bs.modal', event => {
		$('.auto_focus').trigger('focus');
	});
</script>