<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Chatbot</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .chat-container {
            width: 50%;
            margin: 50px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .chat-box {
            height: 300px;
            overflow-y: scroll;
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 10px;
        }

        .input-box {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>
<div class="chat-container bg-light">
    <h3 class="text-center">AI Chatbot</h3>
    <div id="chat-box" class="chat-box bg-white"></div>

    <div class="input-box">
        <input type="text" id="query" class="form-control" placeholder="Type your query...">
        <button id="send-btn" class="btn btn-primary ms-2">Send</button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script>
    $('#send-btn').click(function () {
        let query = $('#query').val();
        $('#query').val('');

        $('#chat-box').append('<p><strong>You:</strong> ' + query + '</p>');

        $.ajax({
            url: "{{ route('chatbot.process') }}",
            method: 'POST',
            data: {
                query: query,
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                $('#chat-box').append('<p><strong>Bot:</strong> ' + response.response + '</p>');
                $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight); // Auto-scroll
            }
        });
    });
</script>
</body>
</html>
