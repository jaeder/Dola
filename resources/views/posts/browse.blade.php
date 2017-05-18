@extends('dola::master')

@section('page_title','All '.$dataType->display_name_plural)

@section('page_header')
    <h1 class="page-title">
        <i class="dola-news"></i> {{ $dataType->display_name_plural }}
        @if (Dola::can('add_'.$dataType->name))
            <a href="{{ route('dola.'.$dataType->slug.'.create') }}" class="btn btn-success">
                <i class="dola-plus"></i> Add New
            </a>
        @endif
    </h1>
    @include('dola::multilingual.language-selector')
@stop

@section('content')
    <div class="page-content container-fluid">
        @include('dola::alerts')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <table id="dataTable" class="table table-hover">
                            <thead>
                                <tr>
                                    @foreach($dataType->browseRows as $row)
                                    <th>{{ $row->display_name }}</th>
                                    @endforeach
                                    <th class="actions">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dataTypeContent as $data)
                                <tr>
                                    @foreach($dataType->browseRows as $row)
                                    <td>
                                        @if($row->type == 'image')
                                            <img src="@if( strpos($data->{$row->field}, 'http://') === false && strpos($data->{$row->field}, 'https://') === false){{ Dola::image( $data->{$row->field} ) }}@else{{ $data->{$row->field} }}@endif" style="width:100px">
                                        @else
                                            @if(is_field_translatable($data, $row))
                                                @include('dola::multilingual.input-hidden', [
                                                    '_field_name'  => $row->field,
                                                    '_field_trans' => get_field_translations($data, $row->field)
                                                ])
                                            @endif
                                            <span>{{ $data->{$row->field} }}</span>
                                        @endif
                                    </td>
                                    @endforeach
                                    <td class="no-sort no-click">
                                        @if (Dola::can('delete_'.$dataType->name))
                                            <div class="btn-sm btn-danger pull-right delete" data-id="{{ $data->id }}">
                                                <i class="dola-trash"></i> Delete
                                            </div>
                                        @endif
                                        @if (Dola::can('edit_'.$dataType->name))
                                            <a href="{{ route('dola.'.$dataType->slug.'.edit', $data->id) }}" class="btn-sm btn-primary pull-right edit">
                                                <i class="dola-edit"></i> Edit
                                            </a>
                                        @endif
                                        @if (Dola::can('read_'.$dataType->name))
                                            <a href="{{ route('dola.'.$dataType->slug.'.show', $data->id) }}" class="btn-sm btn-warning pull-right">
                                                <i class="dola-eye"></i> View
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if (isset($dataType->server_side) && $dataType->server_side)
                            <div class="pull-left">
                                <div role="status" class="show-res" aria-live="polite">Showing {{ $dataTypeContent->firstItem() }} to {{ $dataTypeContent->lastItem() }} of {{ $dataTypeContent->total() }} entries</div>
                            </div>
                            <div class="pull-right">
                                {{ $dataTypeContent->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">
                        <i class="dola-trash"></i> Are you sure you want to delete this {{ $dataType->display_name_singular }}?
                    </h4>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('dola.'.$dataType->slug.'.destroy', ['id' => '__id']) }}" id="delete_form" method="POST">
                        {{ method_field("DELETE") }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right delete-confirm" value="Yes, Delete This {{ $dataType->display_name_singular }}">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    {{-- DataTables --}}
    <script>
        $(document).ready(function () {
            @if (!$dataType->server_side)
                $('#dataTable').DataTable({ "order": [] });
            @endif
            @if ($isModelTranslatable)
                $('.side-body').multilingual();
            @endif
        });

        $('td').on('click', '.delete', function(e) {
            $('#delete_form')[0].action = $('#delete_form')[0].action.replace('__id', $(e.target).data('id'));
            $('#delete_modal').modal('show');
        });
    </script>
    @if($isModelTranslatable)
        <script src="{{ dola_asset('js/multilingual.js') }}"></script>
    @endif
@stop
