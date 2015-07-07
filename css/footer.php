		<!-- FOOTER -->

		<hr>

		<footer>
			<p>&copy; Movies <?= $current_year ?></p>
		</footer>

	</div><!-- .container -->

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

	<script>
	$.getScript('//cdn.jsdelivr.net/isotope/1.5.25/jquery.isotope.min.js', function(){
		$('#top-movies').isotope({
			itemSelector : '.top-movie'
		});
	});
	</script>
</body>
</html>