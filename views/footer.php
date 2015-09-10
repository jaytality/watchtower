<!-- begin footer -->
<footer class="nav navbar-fixed-bottom">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-6">
        <small>
          <span class="text-muted">
            <?php
            $time = microtime();
            $time = explode(' ', $time);
            $time = $time[1] + $time[0];
            $finish = $time;
            $total_time = round(($finish - $start), 4);
            ?>
            Loaded in <?=$total_time?> seconds
          </span>
        </small>
      </div>
      <div class="col-sm-6 text-right">
        <small>
          <span class="text-muted text-right">
            Watchtower &copy; <a href="http://jtiong.com/">Johnathan Tiong</a>, 2015 &bull; [ <a href="https://github.com/jaytwitch/watchtower">Github</a> ]
          </span>
        </small>
      </div>
    </div>
  </div>
</footer>

<!-- scripts -->
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>

</body>
</html>
<?php

$data = array();
