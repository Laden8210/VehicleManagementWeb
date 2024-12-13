<script>
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: '/your-endpoint-for-fetching-events', // Replace with your API route

            eventDidMount: function (info) {
                var tooltip = new bootstrap.Tooltip(info.el, {
                    title: info.event.title, // Just using the title, which has all the data now
                    placement: 'top',
                    trigger: 'hover',
                    html: true
                });
            }
        });

        calendar.render();
    });
</script>