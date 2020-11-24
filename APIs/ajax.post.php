<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ajax Post</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
    <script>
        // $.ajax({url: "https://spktcoop.com/APIs/test.post.php", success: function(result){
        //     console.log('result', result)
        // }});

        $.post("https://spktcoop.com/APIs/test.post.php",{ },
        function(data, status){
            console.log('data', data);
            console.log('status', status);
        });
    </script>
</body>
</html>