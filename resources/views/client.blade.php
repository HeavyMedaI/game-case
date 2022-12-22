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
<div id="client" data-status="{{$client->status}}" data-state="{{$client->state}}" data-x="{{$client->pos[0]}}" data-y="{{$client->pos[1]}}" data-z="{{$client->pos[2]}}">
    <div style="margin-top: 20px">
        <b>Client Status: </b> {!! $client->status == 0 ? '<span id="client-status" style="color: red;">not-connected</span>' : '<span style="color: green;">connected</span>' !!}
        <br />
        <b>Client State: </b> {!! '<span id="client-state" style="color: green;">' . $client->state . '</span>' !!}
        <br />
        <b>Client Position: </b> {!! '<span id="client-position" style="color: black;">x:' . $client->pos[0] . ' y:' . $client->pos[1] . ' z:' . $client->pos[2] . '</span>' !!}
    </div>
</div>

<script type="application/javascript">
    var clientPosition = [parseInt({{$client->pos[0]}}), parseInt({{$client->pos[1]}}), parseInt({{$client->pos[2]}})];
    function sleep(ms) {
        return new Promise(
            resolve => setTimeout(resolve, ms)
        );
    }

    function completeDirective(id){
        console.info('Completing directive: ' + id);
        $.get('directive/complete/'+id, function (res) {
            if(!res.status){
                alert(res.message);
            }else{
                let client = {position: clientPosition[0]+','+clientPosition[1]+','+clientPosition[2]};
                $.post('{{route('client.update', ["id" => 1])}}', {client: client}, function (res){
                    if(!res.status){
                        alert(res.message);
                    }
                });
            }
        });
    }

    function moveAndUpdate(position) {
        clientPosition[0]+=parseInt(position.x);
        clientPosition[1]+=parseInt(position.y);
        clientPosition[2]+=parseInt(position.z);
        $('#client').data('x', clientPosition[0]).data('y', clientPosition[1]).data('z', clientPosition[2]);
        $('#client-position').text('x:' + clientPosition[0] + ' y:' + clientPosition[1] + ' z:' + clientPosition[2]);
        let client = {position: clientPosition[0]+','+clientPosition[1]+','+clientPosition[2]};
        /*$.post('{{route('client.update', ["id" => 1])}}', {client: client}, function (res){
            if(!res.status){
                alert(res.message);
            }
        });*/
    }

     function operateDirective(directive) {
        console.info('operateDirective');
        if(directive.movements.length > 0) {
            $.post('{{route('client.update', ["id" => 1])}}', {client: {state: 'moving'}}, function (res){
                if(!res.status){
                    alert(res.message);
                }else{
                    directive.movements.forEach((pos,i) => {
                        $('#client').data('state', 'moving');
                        $('#client-state').css('color', 'rgb(84, 122, 158)').text('moving');
                        moveAndUpdate(pos);
                    });
                }
            });
        }
        completeDirective(directive.id);
    }

    function checkNewDirectives() {
        $('#client').data('state', 'pending');
        $('#client-state').css('color', 'green').text('pending');
        console.log('Checking new directives...');
        $.get('{{route('directive.new_directives')}}', (directives) => {
            if(directives.length > 0) {
                for(var i = 0; i < directives.length; i++){
                    operateDirective(directives[i]);
                }
                let client = {position: clientPosition[0]+','+clientPosition[1]+','+clientPosition[2], status: 1, state: 'pending'};
                $.post('{{route('client.update', ["id" => 1])}}', {client: client}, function (res){
                    if(res.status){
                        setTimeout(() => {checkNewDirectives();}, 2000);
                    }else{
                        console.error(res.message);
                    }
                });
            }else{
                setTimeout(() => {checkNewDirectives();}, 2000);
            }
        });
    }

    $(function (){
        checkNewDirectives();
    });
</script>
</body>
</html>
