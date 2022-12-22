<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Director Page</title>
    <script type="text/javascript" src="{{asset('js/jquery.js')}}"></script>
</head>
<body>
<div id="movements" style="width: 300px; height: 200px;overflow: hidden;overflow-y: auto;border: 1px solid #aaa;">
    <div class="movement" style="width: 100%;float: left;">
        <ul style="list-style: none;padding: unset;margin: unset;">
            <!--<li>
                <div style="float: left;width: 250px;padding-left: 10px;">xxx</div>
                <div style="float: left;width: 40px;">
                    <button onclick="delDirective(this)" class="directive-delete">Sil</button>
                </div>
            </li>-->
        </ul>


    </div>
</div>
<div style="margin-top: 10px">
    <span>X:</span>
    <input type="number" min="-1" max="1" step="1" id="x" value="0">
    <br><br>
    <span>Y:</span>
    <input type="number" min="-1" max="1" step="1" id="y" value="0">
    <br><br>
    <span>Z:</span>
    <input type="number" min="-1" max="1" step="1" id="z" value="0">
    <br><br>
    <button id="directive-add">Ekle</button>
    <button id="directive-submit">Tümünü Gönder</button>
</div>

<div style="margin-top: 20px">
    <b>Client Status: </b> {!! $client->status == 0 ? '<span id="client-status" style="color: red;">not-connected</span>' : '<span style="color: green;">connected</span>' !!}
    <br />
    <b>Client State: </b> {!! '<span id="client-state" style="color: grey;">' . $client->state . '</span>' !!}
    <br />
    <b>Client Position: </b> {!! '<span id="client-position" style="color: black;">x:' . $client->pos[0] . ' y:' . $client->pos[1] . ' z:' . $client->pos[2] . '</span>' !!}
    <br />
    <a target="_blank" href="{{route('client')}}">Client Screen</a>
</div>

<script type="application/javascript">
    function checkClientState() {
        console.log('Checking client state...');
        $.get('{{route('client.state')}}', function (client) {
            console.info('Client state: ' + client);


            if(client.status == 0){
                $('#client-status').css('color', 'red').text('not-connected');
            }else{
                $('#client-status').css('color', 'green').text('connected');
            }

            if(client.state == 'sleep'){
                $('#client-state').css('color', 'grey').text('sleep');
            }else if(client.state == 'pending'){
                $('#client-state').css('color', 'green').text('pending');
            }else if(client.state == 'moving'){
                $('#client-state').css('color', 'rgb(84, 122, 158)').text('moving');
            }

            $('#client-position').text('x:' + client.pos[0] + ' y:' + client.pos[1] + ' z:' + client.pos[2]);

            setTimeout(function (){
                checkClientState();
            }, 1500);
        });
    }

    function delDirective(e){
        $(e).parents('li').remove();
    }
    $(function (){
        $('#directive-add').click(function (e){
            e.preventDefault();
            let count = $('#movements .movement').length;
            let x = $('input#x').val();
            let y = $('input#y').val();
            let z = $('input#z').val();
            if(x.length > 0 && y.length > 0){
                let _dirHtml = '<li data-x="'+x+'" data-y="'+y+'" data-z="'+z+'"><div style="background: #b6d0d4;width: 100%;height: 22px;border-bottom: .5px solid white;"><div style="float: left;width: 250px;padding-left: 10px;">x:'+x+' y:'+y+' z:'+z+'</div>';
                    _dirHtml += '<div style="float: left;width: 40px;">';
                    _dirHtml += '<button style="background: red;color: white;border: none;border-radius: 3px;" onclick="delDirective(this)" class="directive-delete">Sil</button></div></div></li>';
                $('#movements ul').append(_dirHtml);
            }
        });

        $('#directive-submit').click(function (e){
            e.preventDefault();

            let movements = [];

            $('#movements .movement li').each(function (){
                movements.push({x: $(this).data('x'), y:$(this).data('y'), z:$(this).data('z')});
            });

            $.post('{{route('directive.register')}}', {moves: movements}, function (res){
                if(res.status){
                    $('#movements .movement li').remove();
                }else{
                    alert(res.message);
                }
            });
        });

        checkClientState();
    });
</script>
</body>
</html>
