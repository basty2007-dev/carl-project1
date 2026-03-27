<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function(){
        function loadData(query, sort) {
            $.ajax({
                url: "fetch_customers.php",
                method: "POST",
                data: { q: query, sort: sort },
                success: function(data) {
                    $('#table-body').html(data);
                    if(query.length > 0) { $('#home-btn-wrapper').fadeIn(); } 
                    else { $('#home-btn-wrapper').fadeOut(); }
                }
            });
        }
        $('#live-search').on('keyup', function(){
            loadData($(this).val(), $('#sort-select').val());
        });
        $('#sort-select').on('change', function(){
            loadData($('#live-search').val(), $(this).val());
        });
    });
    </script>
</body>
</html>