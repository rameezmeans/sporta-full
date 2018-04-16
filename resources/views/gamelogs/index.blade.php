<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.jpg') }}">

    <!-- CSFR token for ajax call -->
    <meta name="_token" content="{{ csrf_token() }}"/>

    <title>Manage Game Logs</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    {{-- <link rel="styleeheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> --}}

    <!-- icheck checkboxes -->
    <link rel="stylesheet" href="{{ asset('icheck/square/yellow.css') }}">
    {{-- <link rel="stylesheet" href="https://raw.githubusercontent.com/fronteed/icheck/1.x/skins/square/yellow.css"> --}}

    <!-- toastr notifications -->
    {{-- <link rel="stylesheet" href="{{ asset('toastr/toastr.min.css') }}"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">


    <!-- Font Awesome -->
    {{-- <link rel="stylesheet" href="{{ asset('font-awesome/css/font-awesome.min.css') }}"> --}}
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        .panel-heading {
            padding: 0;
        }
        .panel-heading ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        .panel-heading li {
            float: left;
            border-right:1px solid #bbb;
            display: block;
            padding: 14px 16px;
            text-align: center;
        }
        .panel-heading li:last-child:hover {
            background-color: #ccc;
        }
        .panel-heading li:last-child {
            border-right: none;
        }
        .panel-heading li a:hover {
            text-decoration: none;
        }

        .table.table-bordered tbody td {
            vertical-align: baseline;
        }
    </style>

</head>

<body>


    <div class="col-md-8 col-md-offset-2">
        <div class="row">
            <div class="col-md-10"><h2 class="text-center">Manage Game Logs</h2></div>
            <div class="col-md-2"><a style="float: right;" class="h3" href="{{ url('') }}">Home</a></div>
        </div>
        <br />
        <div class="panel panel-default">
            <div class="panel-heading">
                <ul>
                    <li><i class="fa fa-file-text-o"></i> All the current Game Logs</li>
                    @if($admin)<a href="#" class="add-modal"><li>Add a Game Log</li></a>@endif
                </ul>
            </div>
        
            <div class="panel-body">
                    <table class="table table-striped table-bordered table-hover" id="postTable" style="visibility: hidden;">
                        <thead>
                            <tr>
                                <th valign="middle">#</th>
                                <th>Game</th>
                                <th>Team</th>
                                <th>Opponent</th>
                                @if($admin)
                                    <th>Last updated</th>
                                    <th>Actions</th>
                                @endif
                            </tr>
                            {{ csrf_field() }}
                        </thead>
                        <tbody>
                            @foreach($gamelogs as $indexKey => $gamelog)
                                <tr class="item{{$gamelog->id}} @if($gamelog->is_published) warning @endif">
                                    <td class="col1">{{ $indexKey+1 }}</td>
                                    <td>
                                        {{\App\Game::findOrFail($gamelog->game_id)->name}}
                                    </td>
                                    <td>
                                        {{\App\Team::findOrFail($gamelog->team_id)->name}}
                                    </td>
                                    <td>
                                        {{\App\Team::findOrFail($gamelog->opponent_id)->name}}
                                    </td>
                                    @if($admin)
                                        <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $gamelog->updated_at)->diffForHumans() }}</td>
                                        <td>
                                            <button class="show-modal btn btn-success" data-id="{{$gamelog->id}}"  data-game_id="{{$gamelog->game_id}}" data-game_name="{{\App\Game::findOrFail($gamelog->game_id)->name}}" data-team_id="{{$gamelog->team_id}}" data-team_name="{{\App\Team::findOrFail($gamelog->team_id)->name}}" data-opponent_id="{{$gamelog->opponent_id}}" data-opponent_name="{{\App\Team::findOrFail($gamelog->opponent_id)->name}}">
                                            <span class="glyphicon glyphicon-eye-open"></span> Show</button>
                                            <button class="edit-modal btn btn-info" data-id="{{$gamelog->id}}"  data-game_id="{{$gamelog->game_id}}" data-game_name="{{\App\Game::findOrFail($gamelog->game_id)->name}}" data-team_id="{{$gamelog->team_id}}" data-team_name="{{\App\Team::findOrFail($gamelog->team_id)->name}}" data-opponent_id="{{$gamelog->opponent_id}}" data-opponent_name="{{\App\Team::findOrFail($gamelog->opponent_id)->name}}">
                                            <span class="glyphicon glyphicon-edit"></span> Edit</button>
                                            <button class="delete-modal btn btn-danger" data-id="{{$gamelog->id}}"  data-game_id="{{$gamelog->game_id}}" data-game_name="{{\App\Game::findOrFail($gamelog->game_id)->name}}" data-team_id="{{$gamelog->team_id}}" data-team_name="{{\App\Team::findOrFail($gamelog->team_id)->name}}" data-opponent_id="{{$gamelog->opponent_id}}" data-opponent_name="{{\App\Team::findOrFail($gamelog->opponent_id)->name}}">
                                            <span class="glyphicon glyphicon-trash"></span> Delete</button>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
            </div><!-- /.panel-body -->
        </div><!-- /.panel panel-default -->
    </div><!-- /.col-md-8 -->

    <!-- Modal form to add a post -->
    <div id="addModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="content">Game:</label>
                            <div class="col-sm-10">
                                {{--<input class="form-control"  cols="40" rows="5" type="number">--}}
                                <select class="form-control" id="game_id_add">
                                    @foreach($games as $game)
                                        <option value="{{ $game->id }}">{{ $game->name }}</option>
                                    @endforeach
                                </select>
                                <p class="errorTeamID text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="content">Team:</label>
                            <div class="col-sm-10">
                                {{--<input class="form-control"  cols="40" rows="5" type="number">--}}
                                <select class="form-control" id="team_id_add">
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}">{{ $team->name }}</option>
                                    @endforeach
                                </select>
                                <p class="errorTeamID text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="content">Opponent:</label>
                            <div class="col-sm-10">
                                {{--<input class="form-control"  cols="40" rows="5" type="number">--}}
                                <select class="form-control" id="opponent_id_add">
                                    @foreach($opponents as $opponent)
                                        <option value="{{ $opponent->id }}">{{ $opponent->name }}</option>
                                    @endforeach
                                </select>
                                <p class="errorTeamID text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success add" data-dismiss="modal">
                            <span id="" class='glyphicon glyphicon-check'></span> Add
                        </button>
                        <button type="button" class="btn btn-warning" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

	<!-- Modal form to show a post -->
    <div id="showModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="id">ID:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="id_show" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="content">Game:</label>
                            <div class="col-sm-10">
                                <input class="form-control" id="game_name_show" cols="40" rows="5" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="content">Team:</label>
                            <div class="col-sm-10">
                                <input class="form-control" id="team_name_show" cols="40" rows="5" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="content">Opponent:</label>
                            <div class="col-sm-10">
                                <input class="form-control" id="opponent_name_show" cols="40" rows="5" disabled>
                            </div>
                        </div>
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

	<!-- Modal form to edit a form -->
    <div id="editModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="id">ID:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="id_edit" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="content">Game:</label>
                            <div class="col-sm-10">
                                {{--<textarea class="form-control" id="game_id_edit" cols="40" rows="5"></textarea>--}}
                                <select class="form-control" id="game_id_edit" cols="40" rows="5">
                                    @foreach($games as $game)
                                        <option value="{{ $game->id }}">{{ $game->name }}</option>
                                    @endforeach
                                </select>
                                <p class="errorTeamID text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="content">Team:</label>
                            <div class="col-sm-10">
                                {{--<textarea class="form-control" id="game_id_edit" cols="40" rows="5"></textarea>--}}
                                <select class="form-control" id="team_id_edit" cols="40" rows="5">
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}">{{ $team->name }}</option>
                                    @endforeach
                                </select>
                                <p class="errorTeamID text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="content">Opponent:</label>
                            <div class="col-sm-10">
                                {{--<textarea class="form-control" id="game_id_edit" cols="40" rows="5"></textarea>--}}
                                <select class="form-control" id="opponent_id_edit" cols="40" rows="5">
                                    @foreach($opponents as $opponent)
                                        <option value="{{ $opponent->id }}">{{ $opponent->name }}</option>
                                    @endforeach
                                </select>
                                <p class="errorTeamID text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary edit" data-dismiss="modal">
                            <span class='glyphicon glyphicon-check'></span> Edit
                        </button>
                        <button type="button" class="btn btn-warning" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

	<!-- Modal form to delete a form -->
    <div id="deleteModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <h3 class="text-center">Are you sure you want to delete the following Game Log?</h3>
                    <br />
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="id">ID:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="id_delete" disabled>
                            </div>
                        </div>
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger delete" data-dismiss="modal">
                            <span id="" class='glyphicon glyphicon-trash'></span> Delete
                        </button>
                        <button type="button" class="btn btn-warning" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    {{-- <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script> --}}
    <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>

    <!-- Bootstrap JavaScript -->
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.0.1/js/bootstrap.min.js"></script>

    <!-- toastr notifications -->
    {{-- <script type="text/javascript" src="{{ asset('toastr/toastr.min.js') }}"></script> --}}
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    <!-- icheck checkboxes -->
    <script type="text/javascript" src="{{ asset('icheck/icheck.min.js') }}"></script>

    <!-- Delay table load until everything else is loaded -->
    <script>
        $(window).load(function(){
            $('#postTable').removeAttr('style');
        })
    </script>

    <script>
        $(document).ready(function(){

        });
        
    </script>

    <!-- AJAX CRUD operations -->
    <script type="text/javascript">
        // add a new post
        $(document).on('click', '.add-modal', function() {
            $('.modal-title').text('Add');
            $('#addModal').modal('show');
        });
        $('.modal-footer').on('click', '.add', function() {
            $.ajax({
                type: 'POST',
                url: 'gamelogs',
                data: {
                    '_token': $('input[name=_token]').val(),
                    'name': $('#name_add').val(),
                    'game_id': $('#game_id_add').val(),
                    'team_id': $('#team_id_add').val(),
                    'opponent_id': $('#opponent_id_add').val()
                },
                success: function(data) {
                    $('.errorName').addClass('hidden');
                    $('.errorTeamID').addClass('hidden');

                    if ((data.errors)) {
                        setTimeout(function () {
                            $('#addModal').modal('show');
                            toastr.error('Validation error!', 'Error Alert', {timeOut: 5000});
                        }, 500);

                        if (data.errors.name) {
                            $('.errorName').removeClass('hidden');
                            $('.errorName').text(data.errors.name);
                        }
                        if (data.errors.game_id) {
                            $('.errorTeamID').removeClass('hidden');
                            $('.errorTeamID').text(data.errors.game_id);
                        }
                    } else {
                        toastr.success('Successfully added Game Log!', 'Success Alert', {timeOut: 5000});
                        $('#postTable').prepend("<tr class='item" + data.id + "'><td class='col1'>" + data.id + "</td>" +
                        "<td>" +  data.game_name + "</td>" +
                        "<td>" + data.team_name + "</td>" +
                        "<td>" + data.opponent_name + "</td>" +
                        "" +
                        "<td>Just now!</td><td><button class='show-modal btn btn-success' " +
                        "data-id='" + data.id
                        + "' data-game_id='" + data.game_id + "' data-game_name='" + data.game_name
                        + "' data-team_id='" + data.team_id +"' data-team_name='" + data.team_name
                        + "' data-opponent_id='" + data.opponent_id +"' data-opponent_name='" + data.opponent_name
                        + "'><span class='glyphicon glyphicon-eye-open'></span> Show</button> " +
                        "" +
                        "<button class='edit-modal btn btn-info' " +
                        "data-id='" + data.id + "' " +
                        "' data-game_id='" + data.game_id + "' data-game_name='" + data.game_name +
                        + "' data-team_id='" + data.team_id +"' data-team_name='" + data.team_name
                        + "' data-opponent_id='" + data.opponent_id +"' data-opponent_name='" + data.opponent_name

                        + "'><span class='glyphicon glyphicon-edit'></span>Edit</button><button class='delete-modal btn btn-danger' " +
                        "data-id='" + data.id + "><span class='glyphicon glyphicon-trash'></span> Delete</button></td></tr>");



                    }
                }
            });
        });

        // Show a post
        $(document).on('click', '.show-modal', function() {
            $('.modal-title').text('Show');
            $('#id_show').val($(this).data('id'));
            $('#game_name_show').val($(this).data('game_name'));
            $('#team_name_show').val($(this).data('team_name'));
            $('#opponent_name_show').val($(this).data('opponent_name'));
            $('#showModal').modal('show');
        });


        // Edit a post
        $(document).on('click', '.edit-modal', function() {
            $('.modal-title').text('Edit');
            $('#id_edit').val($(this).data('id'));
            $('#game_id_edit').val($(this).data('game_id'));
            $('#game_name_edit').val($(this).data('game_name'));
            $('#team_id_edit').val($(this).data('team_id'));
            $('#team_name_edit').val($(this).data('team_name'))
            ;$('#opponent_id_edit').val($(this).data('opponent_id'));
            $('#opponent_name_edit').val($(this).data('opponent_name'));
            id = $('#id_edit').val();
            $('#editModal').modal('show');
        });
        $('.modal-footer').on('click', '.edit', function() {
            $.ajax({
                type: 'PUT',
                url: 'gamelogs/' + id,
                data: {
                    '_token': $('input[name=_token]').val(),
                    'id': $("#id_edit").val(),
                    'name': $('#name_edit').val(),
                    'game_id': $('#game_id_edit').val(),
                    'team_id': $('#team_id_edit').val(),
                    'opponent_id': $('#opponent_id_edit').val()
                },
                success: function(data) {
                    $('.errorName').addClass('hidden');
                    $('.errorTeamID').addClass('hidden');

                    if ((data.errors)) {
                        setTimeout(function () {
                            $('#editModal').modal('show');
                            toastr.error('Validation error!', 'Error Alert', {timeOut: 5000});
                        }, 500);

                        if (data.errors.name) {
                            $('.errorName').removeClass('hidden');
                            $('.errorName').text(data.errors.name);
                        }
                        if (data.errors.game_id) {
                            $('.errorTeamID').removeClass('hidden');
                            $('.errorTeamID').text(data.errors.game_id);
                        }
                    } else {
                        toastr.success('Successfully updated Game Log!', 'Success Alert', {timeOut: 5000});
                        $('.item' + data.id).replaceWith("<tr class='item" + data.id + "'><td class='col1'>" + data.id + "</td><td>" + data.game_name + "</td><td>" + data.team_name + "</td><td>" + data.oppoent_name + "</td><td>Right now</td><td><button class='show-modal btn btn-success' data-id='" + data.id + "' data-name='" + data.name
                        + "' data-game_id='" + data.game_id +"' data-game_name='" + data.game_name
                        + "' data-team_id='" + data.team_id +"' data-team_name='" + data.team_name
                        + "' data-opponent_id='" + data.opponent_id +"' data-opponent_name='" + data.opponent_name
                        + "'><span class='glyphicon glyphicon-eye-open'></span> Show</button> <button class='edit-modal btn btn-info' " +
                        "data-id='" + data.id +
                        "' data-game_id='" + data.game_id +"' data-game_name='" + data.game_name +
                        "' data-team_id='" + data.team_id +"' data-team_name='" + data.team_name +
                        "' data-opponent_id='" + data.opponent_id +"' data-opponent_name='" + data.opponent_name +

                        "'><span class='glyphicon glyphicon-edit'></span> Edit</button> <button class='delete-modal btn btn-danger' data-id='" + data.id + "' data-name='" + data.name + "' data-game_id='" + data.game_id + "' data-game_name='" + data.game_name + "'><span class='glyphicon glyphicon-trash'></span> Delete</button></td></tr>");

                        if (data.is_published) {
                            $('.edit_published').prop('checked', true);
                            $('.edit_published').closest('tr').addClass('warning');
                        }
                        $('.edit_published').iCheck({
                            checkboxClass: 'icheckbox_square-yellow',
                            radioClass: 'iradio_square-yellow',
                            increaseArea: '20%'
                        });
                        $('.edit_published').on('ifToggled', function(event) {
                            $(this).closest('tr').toggleClass('warning');
                        });
                        $('.edit_published').on('ifChanged', function(event){
                            id = $(this).data('id');
                            $.ajax({
                                type: 'POST',
                                url: "{{ URL::route('changeStatus') }}",
                                data: {
                                    '_token': $('input[name=_token]').val(),
                                    'id': id
                                },
                                success: function(data) {
                                    // empty
                                }
                            });
                        });
                        $('.col1').each(function (index) {
                            $(this).html(index+1);
                        });
                    }
                }
            });
        });
        
        // delete a post
        $(document).on('click', '.delete-modal', function() {
            $('.modal-title').text('Delete');
            $('#id_delete').val($(this).data('id'));
            $('#name_delete').val($(this).data('name'));
            $('#deleteModal').modal('show');
            id = $('#id_delete').val();
        });
        $('.modal-footer').on('click', '.delete', function() {
            $.ajax({
                type: 'DELETE',
                url: 'gamelogs/' + id,
                data: {
                    '_token': $('input[name=_token]').val()
                },
                success: function(data) {
                    toastr.success('Successfully deleted Game Log!', 'Success Alert', {timeOut: 5000});
                    $('.item' + data['id']).remove();
                    $('.col1').each(function (index) {
                        $(this).html(index+1);
                    });
                }
            });
        });
    </script>

</body>
</html>