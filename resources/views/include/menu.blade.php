<ul class="navigation clearfix">
	<li class="dropdown current"><a href="#">Home</a>
		<ul>
			<li><a href="{{ url('/') }}">Home</a></li>
			<!-- <li><a href="index2.php">City Tou</a></li> -->
			<!-- <li><a href="index3.php">Tour Package</a></li> -->
		</ul>
	</li>
	<li><a href="{{ route('aboutUs') }}">About</a></li>
	<li class="dropdown"><a>Tours</a>
		<ul>
			<!-- <li><a href="tour-list.php">Tour List</a></li> -->
			<li><a href="{{ route('tourpage') }}">Tour</a></li>
			<!-- <li><a href="tour-sidebar.php">Tour Sidebar</a></li> -->
			<li><a href="{{ route('tourDetailpage') }}">Tour Details</a></li>
			<!-- <li><a href="tour-guide.php">Tour Guide</a></li> -->
		</ul>
	</li>
	<li class="dropdown"><a>Destinations</a>
		<ul>
			<!-- <li><a href="destination1.php">Destination 01</a></li> -->
			<li><a href="{{ route('destinationpage') }}">Destination</a></li>
			<li><a href="{{ route('destinationDetailpage') }}">Destination Details</a></li>
		</ul>
	</li>
	<li class="dropdown"><a href="#">Pages</a>
		<ul>
			<!-- <li><a href="pricing.php">Pricing</a></li> -->
			<!-- <li><a href="faqs.php">faqs</a></li> -->
			<li class="dropdown"><a href="#">Gallery</a>
				<ul>
					<li><a href="gellery-grid.php">Gallery Grid</a></li>
					<li><a href="gellery-slider.php">Gallery Slider</a></li>
				</ul>
			</li>
			<!-- <li class="dropdown"><a href="#">products</a>
				<ul>
					<li><a href="shop.php">Our Products</a></li>
					<li><a href="product-details.php">Product Details</a></li>
				</ul>
			</li> -->
			<li><a href="contact.php">Contact Us</a></li>
			<!-- <li><a href="404.php">404 Error</a></li> -->
		</ul>
	</li>
	<li class="dropdown"><a href="#">blog</a>
		<ul>
			<li><a href="blog.php">blog List</a></li>
			<li><a href="blog-details.php">blog details</a></li>
		</ul>
	</li>
</ul>