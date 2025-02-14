<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="container mt-4">
    <h2 class="mb-4">Online Users</h2>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>Last Seen</th>
            </tr>
        </thead>
        <tbody id="user-table-body">
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td class="last-seen" data-time="{{ $user->last_seen }}">
                        {{ $user->last_seen ? \Carbon\Carbon::parse($user->last_seen)->diffForHumans() : 'Never' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        function updateLastSeen() {
            $.ajax({
                url: '/fetch-online-users',
                method: 'GET',
                success: function(users) {
                    let tbody = $('#user-table-body');
                    tbody.empty();
                    let now = moment(); // Get current time

                    users.forEach(user => {
                        let momentTime = moment(user.last_seen);
                        let diffMinutes = now.diff(momentTime, 'minutes');
                        let diffSeconds = now.diff(momentTime, 'seconds');
                        let lastSeenText = 'Never';

                        // Consider the user online if last seen is within 1 minute
                        if (diffSeconds < 60) {
                            lastSeenText = '<span class="badge bg-success">Online</span>';
                        } else if (diffMinutes < 60) {
                            lastSeenText = momentTime.fromNow();
                        } else if (diffMinutes < 1440) {
                            lastSeenText = momentTime.format('HH:mm');
                        } else {
                            lastSeenText = momentTime.format('MMM D, YYYY HH:mm');
                        }

                        tbody.append(`
                            <tr>
                                <td>${user.name}</td>
                                <td class="last-seen">${lastSeenText}</td>
                            </tr>
                        `);
                    });
                }
            });
        }

        $(document).ready(function() {
            updateLastSeen();
            setInterval(updateLastSeen, 10000); // Update every 10 seconds
        });
    </script>
</body>
</html>
