@php
    $edit = !is_null($dataTypeContent->getKey());
    $add = is_null($dataTypeContent->getKey());
@endphp

@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', __('voyager::generic.' . ($edit ? 'edit' : 'add')) . ' ' .
    $dataType->getTranslatedAttribute('display_name_singular'))

@section('page_header')
    <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i>
        {{ __('voyager::generic.' . ($edit ? 'edit' : 'add')) . ' ' . $dataType->getTranslatedAttribute('display_name_singular') }}
    </h1>
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered">
                    <!-- form start -->
                    <form role="form" class="form-edit-add"
                        action="{{ $edit ? route('voyager.' . $dataType->slug . '.update', $dataTypeContent->getKey()) : route('voyager.' . $dataType->slug . '.store') }}"
                        method="POST" enctype="multipart/form-data">
                        <!-- PUT Method if we are editing -->
                        @if ($edit)
                            {{ method_field('PUT') }}
                        @endif

                        <!-- CSRF TOKEN -->
                        {{ csrf_field() }}

                        <div class="panel-body">

                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Adding / Editing -->
                            @php
                                $dataTypeRows = $dataType->{$edit ? 'editRows' : 'addRows'};
                            @endphp

                            @foreach ($dataTypeRows as $row)
                                <!-- GET THE DISPLAY OPTIONS -->
                                @php
                                    $display_options = $row->details->display ?? null;
                                    if ($dataTypeContent->{$row->field . '_' . ($edit ? 'edit' : 'add')}) {
                                        $dataTypeContent->{$row->field} =
                                            $dataTypeContent->{$row->field . '_' . ($edit ? 'edit' : 'add')};
                                    }
                                @endphp
                                @if (isset($row->details->legend) && isset($row->details->legend->text))
                                    <legend class="text-{{ $row->details->legend->align ?? 'center' }}"
                                        style="background-color: {{ $row->details->legend->bgcolor ?? '#f0f0f0' }};padding: 5px;">
                                        {{ $row->details->legend->text }}</legend>
                                @endif

                                <div class="form-group @if ($row->type == 'hidden') hidden @endif col-md-{{ $display_options->width ?? 12 }} {{ $errors->has($row->field) ? 'has-error' : '' }}"
                                    @if (isset($display_options->id)) {{ "id=$display_options->id" }} @endif>
                                    {{ $row->slugify }}
                                    <label class="control-label"
                                        for="name">{{ $row->getTranslatedAttribute('display_name') }}</label>
                                    @include('voyager::multilingual.input-hidden-bread-edit-add')
                                    @if ($add && isset($row->details->view_add))
                                        @include($row->details->view_add, [
                                            'row' => $row,
                                            'dataType' => $dataType,
                                            'dataTypeContent' => $dataTypeContent,
                                            'content' => $dataTypeContent->{$row->field},
                                            'view' => 'add',
                                            'options' => $row->details,
                                        ])
                                    @elseif ($edit && isset($row->details->view_edit))
                                        @include($row->details->view_edit, [
                                            'row' => $row,
                                            'dataType' => $dataType,
                                            'dataTypeContent' => $dataTypeContent,
                                            'content' => $dataTypeContent->{$row->field},
                                            'view' => 'edit',
                                            'options' => $row->details,
                                        ])
                                    @elseif (isset($row->details->view))
                                        @include($row->details->view, [
                                            'row' => $row,
                                            'dataType' => $dataType,
                                            'dataTypeContent' => $dataTypeContent,
                                            'content' => $dataTypeContent->{$row->field},
                                            'action' => $edit ? 'edit' : 'add',
                                            'view' => $edit ? 'edit' : 'add',
                                            'options' => $row->details,
                                        ])
                                    @elseif ($row->type == 'relationship')
                                        @include('voyager::formfields.relationship', [
                                            'options' => $row->details,
                                        ])
                                    @else
                                        {!! app('voyager')->formField($row, $dataType, $dataTypeContent) !!}
                                    @endif

                                    @foreach (app('voyager')->afterFormFields($row, $dataType, $dataTypeContent) as $after)
                                        {!! $after->handle($row, $dataType, $dataTypeContent) !!}
                                    @endforeach
                                    @if ($errors->has($row->field))
                                        @foreach ($errors->get($row->field) as $error)
                                            <span class="help-block">{{ $error }}</span>
                                        @endforeach
                                    @endif
                                </div>
                            @endforeach

                        </div><!-- panel-body -->

                        <div class="panel-footer">
                        @section('submit-buttons')
                            <button type="submit" class="btn btn-primary save">{{ __('voyager::generic.save') }}</button>
                        @stop
                        @yield('submit-buttons')
                    </div>
                </form>

                <div style="display:none">
                    <input type="hidden" id="upload_url" value="{{ route('voyager.upload') }}">
                    <input type="hidden" id="upload_type_slug" value="{{ $dataType->slug }}">
                </div>
            </div>
            @if ($edit)
                {{-- FR --}}
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <a href="{{ route('videoonline.videos.create', $dataTypeContent->id) }}"
                            class="btn btn-success btn-add-new">
                            <i class="voyager-plus"></i> <span>Ajouter une vidéo</span>
                        </a>
                        <a href="#" id="reorderVideos" class="btn btn-primary">
                            <span>Enregistrer l'ordre des vidéos</span>
                        </a>
                        <h3>Vidéos associées (Français)</h3>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Ordre</th>
                                        <th>Titre</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="sortable-videos">
                                    @forelse($dataTypeContent->videos->sortBy('order') as $video)
                                        <tr data-id="{{ $video->id }}">
                                            <td class="handle"><i class="voyager-handle"></i></td>
                                            <td>{{ $video->title }}</td>
                                            <td>
                                                <a href="{{ route('videoonline.videos.edit.edit', $video->id) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="voyager-edit"></i> Modifier
                                                </a>
                                                <button class="btn btn-sm btn-danger delete"
                                                    data-id="{{ $video->id }}" id="delete-{{ $video->id }}">
                                                    <i class="voyager-trash"></i> Eliminar
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3">Aucune vidéo associée.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{-- END FR --}}

                {{-- EN --}}
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <a href="{{ route('videoonline.en.videos.create', $dataTypeContent->id) }}"
                            class="btn btn-success btn-add-new">
                            <i class="voyager-plus"></i> <span>Ajouter une vidéo</span>
                        </a>
                        <a href="#" id="reorderVideosEn" class="btn btn-primary">
                            <span>Enregistrer l'ordre des vidéos</span>
                        </a>
                        <h3>Vidéos associées (Anglais)</h3>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Ordre</th>
                                        <th>Titre</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="sortable-videos-en">
                                    @forelse($dataTypeContent->videosEn->sortBy('order') as $video)
                                        <tr data-id="{{ $video->id }}">
                                            <td class="handle"><i class="voyager-handle"></i></td>
                                            <td>{{ $video->title }}</td>
                                            <td>
                                                <a href="{{ route('videoonline.en.videos.edit.edit', $video->id) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="voyager-edit"></i> Modifier
                                                </a>
                                                <button class="btn btn-sm btn-danger delete-en"
                                                    data-id="{{ $video->id }}" id="delete-{{ $video->id }}">
                                                    <i class="voyager-trash"></i> Eliminar
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3">Aucune vidéo associée.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{-- END EN --}}
            @endif
        </div>
    </div>
</div>

{{-- Modal de confirmación para eliminar FR --}}
<div class="modal fade modal-danger" id="confirm_delete_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="voyager-warning"></i> {{ __('voyager::generic.are_you_sure') }}</h4>
            </div>
            <div class="modal-body">
                <h4>{{ __('voyager::generic.are_you_sure_delete') }} '<span class="confirm_delete_name"></span>'</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                    data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                <button type="button" class="btn btn-danger"
                    id="confirm_delete">{{ __('voyager::generic.delete_confirm') }}</button>
            </div>
        </div>
    </div>
</div>


{{-- Modal de confirmación para eliminar EN --}}
<div class="modal fade modal-danger" id="confirm_delete_modal_en">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="voyager-warning"></i> {{ __('voyager::generic.are_you_sure') }}
                </h4>
            </div>
            <div class="modal-body">
                <h4>{{ __('voyager::generic.are_you_sure_delete') }} '<span class="confirm_delete_name_en"></span>'
                </h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                    data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                <button type="button" class="btn btn-danger"
                    id="confirm_delete_en">{{ __('voyager::generic.delete_confirm') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- End Delete File Modal -->
@stop

@section('javascript')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script>
    var params = {};
    var $file;

    function deleteHandler(tag, isMulti) {
        return function() {
            $file = $(this).siblings(tag);

            params = {
                slug: '{{ $dataType->slug }}',
                filename: $file.data('file-name'),
                id: $file.data('id'),
                field: $file.parent().data('field-name'),
                multi: isMulti,
                _token: '{{ csrf_token() }}'
            }

            $('.confirm_delete_name').text(params.filename);
            $('#confirm_delete_modal').modal('show');
        };
    }

    $('document').ready(function() {

        $('.delete').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            $('.confirm_delete_name').text('Video ' + id); // Cambia el texto según sea necesario
            $('#confirm_delete').data('id', id); // Guarda el ID en el botón de confirmación
            $('#confirm_delete_modal').modal('show'); // Muestra el modal
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#confirm_delete').on('click', function() {
            var id = $(this).data('id');
            $.ajax({
                url: "{{ route('videoonline.videos.destroy', ':id') }}".replace(':id', id),
                type: 'DELETE',
                success: function(result) {
                    toastr.success('Video eliminado correctamente.');
                    location.reload(); // Recarga la página para reflejar los cambios
                },
                error: function() {
                    toastr.error('Error al eliminar el video.');
                }
            });
            $('#confirm_delete_modal').modal('hide'); // Oculta el modal
        });

        $('.toggleswitch').bootstrapToggle();

        //Init datepicker for date fields if data-datepicker attribute defined
        //or if browser does not handle date inputs
        $('.form-group input[type=date]').each(function(idx, elt) {
            if (elt.hasAttribute('data-datepicker')) {
                elt.type = 'text';
                $(elt).datetimepicker($(elt).data('datepicker'));
            } else if (elt.type != 'date') {
                elt.type = 'text';
                $(elt).datetimepicker({
                    format: 'L',
                    extraFormats: ['YYYY-MM-DD']
                }).datetimepicker($(elt).data('datepicker'));
            }
        });

        $("#sortable-videos").sortable({
            handle: '.handle',
            update: function(event, ui) {
                // Esta función se llama cuando se cambia el orden
            }
        });

        $("#reorderVideos").on('click', function(e) {
            e.preventDefault();
            var order = $("#sortable-videos").sortable("toArray", {
                attribute: 'data-id'
            });
            $.post('{{ route('videos.reorder') }}', {
                order: order,
                _token: '{{ csrf_token() }}'
            }, function(data) {
                toastr.success('El orden de los videos ha sido actualizado.');
            });
        });

        @if ($isModelTranslatable)
            $('.side-body').multilingual({
                "editing": true
            });
        @endif

        $('.side-body input[data-slug-origin]').each(function(i, el) {
            $(el).slugify();
        });

        $('.form-group').on('click', '.remove-multi-image', deleteHandler('img', true));
        $('.form-group').on('click', '.remove-single-image', deleteHandler('img', false));
        $('.form-group').on('click', '.remove-multi-file', deleteHandler('a', true));
        $('.form-group').on('click', '.remove-single-file', deleteHandler('a', false));



    });
</script>

<script>
    $(document).ready(function() {
        $("#sortable-videos-en").sortable({
            handle: '.handle',
            update: function(event, ui) {
                // Esta función se llama cuando se cambia el orden
            }
        });

        $("#reorderVideosEn").on('click', function(e) {
            e.preventDefault();
            var order = $("#sortable-videos-en").sortable("toArray", {
                attribute: 'data-id'
            });
            $.post('{{ route('videos.en.reorder') }}', {
                order: order,
                _token: '{{ csrf_token() }}'
            }, function(data) {
                toastr.success('El orden de los videos ha sido actualizado.');
            });
        });


        $('.delete-en').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            $('.confirm_delete_name_en').text('Video ' + id); // Cambia el texto según sea necesario
            $('#confirm_delete_en').data('id', id); // Guarda el ID en el botón de confirmación
            $('#confirm_delete_modal_en').modal('show'); // Muestra el modal
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $('#confirm_delete_en').on('click', function() {
            var id = $(this).data('id');
            $.ajax({
                url: "{{ route('videoonline.en.videos.destroy', ':id') }}".replace(':id', id),
                type: 'DELETE',
                success: function(result) {
                    toastr.success('Video eliminado correctamente.');
                    location.reload(); // Recarga la página para reflejar los cambios
                },
                error: function() {
                    toastr.error('Error al eliminar el video.');
                }
            });
            $('#confirm_delete_modal_en').modal('hide'); // Oculta el modal
        });
    });
</script>
@stop
