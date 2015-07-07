		<div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar">

			<div class="panel panel-default">
				<div class="panel-heading">Archives</div>
				<div class="panel-body">
					<ol class="list-unstyled">
						<?php
						for($i = 0; $i < 12; $i++) {

							$time = strtotime('-'.$i.' month');

							$year = date('Y', $time);
							$month_en = strtolower(date('F', $time));
							$month_fr = ucfirst(getMonthLabel($month_en));
							$date_label = $month_fr.' '.$year;
							$date_value = date('Y-m', $time);
						?>
						<li><a href="?date=<?= $date_value ?>"><?= $date_label ?></a></li>
						<?php }	?>
					</ol>
				</div>
			</div>

		</div>