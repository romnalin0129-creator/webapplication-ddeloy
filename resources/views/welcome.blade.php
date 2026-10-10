@extends('template.frontend.master')

@section('content')
    

<section class="featured-posts">
    <div class="container">
        <div class="row no-gutters">
            <div class="col-md-6 col-xs-12 col-lg-4">
                <div class="featured-slider mr-md-3 mr-lg-3">
                    <div class="item" style="background-image:url(images/news/img-1.jpg)">
                        <div class="post-content">
                            <a href="#" class="post-cat bg-primary">Entertainment</a>
                            <h2 class="slider-post-title">
                                <a href="single-post.html">Here's How To Get Free Pizza On</a>
                            </h2>
                            <div class="post-meta mt-2">
                                <span class="posted-time"><i class="fa fa-clock-o mr-2 text-danger"></i>19 hours ago</span>
                                <span class="post-author">
                                    by
                                    <a href="author.html">Rodinho Summon</a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xs-12 col-lg-4">
                 <div class="featured-slider mr-lg-3">
                    <div class="item" style="background-image:url(images/news/img-2.jpg)">
                        <div class="post-content">
                            <a href="#" class="post-cat bg-danger">game</a>
                            <h2 class="slider-post-title">
                                <a href="single-post.html">Call Of Duty: Black Ops 4 Releasing</a>
                            </h2>
                            <div class="post-meta mt-2">
                                <span class="posted-time"><i class="fa fa-clock-o mr-2 text-danger"></i>19 hours ago</span>
                                <span class="post-author">
                                    <span> by </span>
                                    <a href="author.html">Rodinho Summon</a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-12 col-xs-12 col-sm-12 col-lg-4">
                <div class="row mt-3 mt-lg-0">
                    <div class="col-lg-12 col-xs-12 col-sm-6 col-md-6">
                        <div class="post-featured-style" style="background-image:url(images/news/news-02.jpg)">
                            <div class="post-content">
                                <a href="#" class="post-cat bg-success">lifestyle</a>
                                <h2 class="post-title">
                                    <a href="single-post.html">Healthy diet to keep you healthy and strong</a>
                                </h2>
                                <div class="post-meta mt-2">
                                    <span class="posted-time"><i class="fa fa-clock-o mr-2 text-danger"></i>19 hours ago</span>
                                    <span class="post-author">
                                        <span> by </span>
                                        <a href="author.html">Rodinho Summon</a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-xs-12 col-sm-6 col-md-6">
                        <div class="post-featured-style" style="background-image:url(images/news/news-04.jpg)">
                            <div class="post-content">
                                <a href="#" class="post-cat bg-info">travel</a>
                                <h2 class="post-title">
                                    <a href="single-post.html">Call Of Duty: Black Ops 4 Releasing</a>
                                </h2>
                                <div class="post-meta mt-2">
                                    <span class="posted-time"><i class="fa fa-clock-o mr-2 text-danger"></i>19 hours ago</span>
                                    <span class="post-author">
                                        <span> by </span>
                                        <a href="author.html">Rodinho Summon</a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@livewire('editor')

<section class="news-style-four bg-light section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="block">
                    <h3 class="news-title">
                        <span>Tour</span>
                    </h3>
                    <div class="post-overlay-wrapper clearfix">
                        <div class="post-thumbnail">
                            <img class="img-fluid" src="images/news/news-13.jpg" alt="post-thumbnail"/>
                        </div>

                        <div class="post-content">
                            <h2 class="post-title ">
                                <a href="single-post.html">An Asteroid Is Passing Earth Today: Here's How to</a>
                            </h2>
                            <div class="post-meta white">
                                <span class="posted-time">2 hours ago</span>
                                <span class="post-author">by
                                    <a href="author.html">Rock Madveen</a>
                                </span>
                                <span class="pull-right">
                                    <i class="fa fa-comments"></i>
                                    <a href="single-post.html#comments">05</a>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="post-list-block">
                        <div class="post-block-wrapper post-float clearfix">
                            <div class="post-thumbnail">
                                <img class="img-fluid" src="images/news/news-11.jpg" alt="post-thumbnail"/>
                                
                            </div>

                            <div class="post-content">
                                <h2 class="post-title title-sm">
                                    <a href="single-post.html">Snow and Freezing Rain in Paris Forces the</a>
                                </h2>
                                <div class="post-meta">
                                    <span class="posted-time">3 hours ago</span>
                                </div>
                            </div>
                        </div>

                        <div class="post-block-wrapper post-float clearfix">
                            <div class="post-thumbnail">
                                <img class="img-fluid" src="images/news/news-04.jpg" alt="post-thumbnail"/>
                            </div>
                            <div class="post-content">
                                <h2 class="post-title title-sm">
                                    <a href="single-post.html">Your social media apps want to help.</a>
                                </h2>
                                <div class="post-meta">
                                    <span class="posted-time">8 hours ago</span>
                                </div>
                            </div>
                        </div>

                        <div class="post-block-wrapper post-float clearfix">
                            <div class="post-thumbnail">
                                <img class="img-fluid" src="images/news/news-12.jpg" alt="post-thumbnail"/>
                            </div>

                            <div class="post-content">
                                <h2 class="post-title title-sm">
                                    <a href="single-post.html">Today Is the Day Most People Quit Their New Year's</a>
                                </h2>
                                <div class="post-meta">
                                    <span class="posted-time">9 hours ago</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="block">
                    <h3 class="news-title">
                        <span>Game</span>
                    </h3>
                    <div class="post-overlay-wrapper clearfix">
                        <div class="post-thumbnail">
                            <img class="img-fluid" src="images/news/news-08.jpg" alt="post-thumbnail"/>
                        </div>

                        <div class="post-content">
                            <h2 class="post-title">
                                <a href="single-post.html">Call Of Duty: Black Ops 4 Releasing</a>
                            </h2>
                            <div class="post-meta white">
                                <span class="posted-time">3 hours ago</span>
                                <span class="post-author">by
                                    <a href="author.html">Arya Stark</a>
                                </span>
                                <span class="pull-right">
                                    <i class="fa fa-comments"></i>
                                    <a href="single-post.html#comments">10</a>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="post-list-block">
                        <div class="post-block-wrapper post-float clearfix">
                            <div class="post-thumbnail">
                                <img class="img-fluid" src="images/news/news-03.jpg" alt="post-thumbnail"/>
                            </div>

                            <div class="post-content">
                                <h2 class="post-title title-sm">
                                    <a href="single-post.html">Apple DashboardPod review: locked in</a>
                                </h2>
                                <div class="post-meta">
                                    <span class="posted-time">4 hours ago</span>
                                </div>
                            </div>
                        </div>

                        <div class="post-block-wrapper post-float clearfix">
                            <div class="post-thumbnail">
                                <img class="img-fluid" src="images/news/news-01.jpg" alt="post-thumbnail"/>
                            </div>

                            <div class="post-content">
                                <h2 class="post-title title-sm">
                                    <a href="single-post.html">Ex-Googler warns coding bootcamps are lacking</a>
                                </h2>
                                <div class="post-meta">
                                    <span class="posted-time">5 hours ago</span>
                                </div>
                            </div>
                        </div>

                        <div class="post-block-wrapper post-float clearfix">
                            <div class="post-thumbnail">
                                <img class="img-fluid" src="images/news/news-06.jpg" alt="post-thumbnail"/>
                            </div>

                            <div class="post-content">
                                <h2 class="post-title title-sm">
                                    <a href="single-post.html">PS4 Games Sale: All The PSN Deals</a>
                                </h2>
                                <div class="post-meta">
                                    <span class="posted-time">12 hours ago</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="block">
                    <h3 class="news-title">
                        <span>Health</span>
                    </h3>
                    <div class="post-overlay-wrapper clearfix">
                        <div class="post-thumbnail">
                            <img class="img-fluid" src="images/news/news-05.jpg" alt="post-thumbnail"/>
                        </div>

                        <div class="post-content">
                            <h2 class="post-title">
                                <a href="single-post.html">Here's How To Get Free Pizza On</a>
                            </h2>
                            <div class="post-meta white">
                                <span class="posted-time">an hour ago</span>
                                <span class="post-author">by
                                    <a href="author.html">Mad King</a>
                                </span>
                                <span class="pull-right">
                                    <i class="fa fa-comments"></i>
                                    <a href="single-post.html#comments">30</a>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="post-list-block">
                        <div class="post-block-wrapper post-float clearfix">
                            <div class="post-thumbnail">
                                <img class="img-fluid" src="images/news/news-10.jpg" alt="post-thumbnail"/>
                            </div>

                            <div class="post-content">
                                <h2 class="post-title title-sm">
                                    <a href="single-post.html">Free Two-Hour Delivery From Whole Foods</a>
                                </h2>
                                <div class="post-meta">
                                    <span class="posted-time">2 hours ago</span>
                                </div>
                            </div>
                        </div>

                        <div class="post-block-wrapper post-float clearfix">
                            <div class="post-thumbnail">
                                <img class="img-fluid" src="images/news/news-07.jpg" alt="post-thumbnail"/>
                            </div>

                            <div class="post-content">
                                <h2 class="post-title title-sm">
                                    <a href="single-post.html">Your social media apps want to help</a>
                                </h2>
                                <div class="post-meta">
                                    <span class="posted-time">4 hours ago</span>
                                </div>
                            </div>
                        </div>

                        <div class="post-block-wrapper post-float clearfix">
                            <div class="post-thumbnail">
                                <img class="img-fluid" src="images/news/news-14.jpg" alt="post-thumbnail"/>
                            </div>

                            <div class="post-content">
                                <h2 class="post-title title-sm">
                                    <a href="single-post.html">Snow and Freezing Rain in Paris Forces the</a>
                                </h2>
                                <div class="post-meta">
                                    <span class="posted-time">9 hours ago</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="section-padding">
	<div class="container">
		<div class="row">
			<div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
				<div class="all-news-block">
    <h3 class="news-title">
        <span>Latest Articles</span>
    </h3>
    <div class="all-news">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="post-block-wrapper post-float-half clearfix">
                    <div class="post-thumbnail">
                        <a href="single-post.html">
                            <img class="img-fluid" src="images/news/news-01.jpg" alt="post-thumbnail"/>
                        </a>
                    </div>
                    <div class="post-content">
                        <a class="post-category" href="post-category-2.html">Google</a>
                        <h2 class="post-title mt-3">
                            <a href="single-post.html">Ex-Googler warns coding bootcamps are lacking</a>
                        </h2>
                        <div class="post-meta">
                            <span class="posted-time">an hour ago</span>
                            <span class="post-author">by
                                <a href="author.html">John Snow</a>
                            </span>
                        </div>
                        <p>
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Vel eaque, aliquid accusamus 
                            soluta!...
                        </p>
                    </div>
                </div>
            </div>
                
            <div class="col-lg-6 col-md-6">
                <div class="post-block-wrapper post-float-half clearfix">
                   <div class="post-thumbnail">
                        <a href="single-post.html">
                            <img class="img-fluid" src="images/news/news-12.jpg" alt="post-thumbnail"/>
                        </a>
                    </div>
                    <div class="post-content">
                        <a class="post-category" href="post-category-2.html">Health</a>
                        <h2 class="post-title mt-3">
                            <a href="single-post.html">Today Is the Day Most People Quit Their New Year's Party</a>
                        </h2>
                        <div class="post-meta">
                            <span class="posted-time">4 hours ago</span>
                            <span class="post-author">by
                                <a href="author.html">Rimmon Ronnick</a>
                            </span>
                        </div>
                        <p>
                            Lorem ipsum dolor sit amet consectetur, adipisicing elit. Corporis blanditiis hic 
                            cumque excepturi...
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
				<nav aria-label="pagination-wrapper" class="pagination-wrapper">
  <ul class="pagination justify-content-center">
    <li class="page-item active">
      <a class="page-link" href="#" aria-label="Previous">
        <span aria-hidden="true"><i class="fa fa-angle-double-left mr-2"></i></span>
        <span class="">Previous</span>
      </a>
    </li>
    <li class="page-item"><a class="page-link" href="#">1</a></li>
    <li class="page-item"><a class="page-link" href="#">2</a></li>
    <li class="page-item"><a class="page-link" href="#">3</a></li>
    <li class="page-item">
      <a class="page-link" href="#" aria-label="Next">
        <span class="">Next</span>
        <span aria-hidden="true"><i class="fa fa-angle-double-right ml-2"></i></span>
      </a>
    </li>
  </ul>
</nav>				
			</div>
			<div class="col-lg-4 col-md-8 col-sm-12 col-xs-12">
				<div class="sidebar sidebar-right">
					<div class="widget">
    <h3 class="news-title">
        <span>Top Authors</span>
    </h3>
    <div class="post-list-block">
        <div class=" review-post-list">
            <div class="top-author">
                <img src="images/news/author-01.jpg" alt="author-thumb">
                <div class="info">
                    <h4 class="name"><a href="author.html">Jack Rockshow</a></h4>
                    <ul class="list-unstyled">
                        <li>37 Posts</li>
                    </ul>
                </div>
            </div>
            <div class="top-author">
                <img src="images/news/author-02.jpg" alt="author-thumb">
                <div class="info">
                    <h4 class="name"><a href="author.html">Lint Handson</a></h4>
                    <ul class="list-unstyled">
                        <li>28 Posts</li>
                    </ul>
                </div>
            </div>
            <div class="top-author">
                <img src="images/news/author-03.jpg" alt="author-thumb">
                <div class="info">
                    <h4 class="name"><a href="author.html">Ronny Robeen</a></h4>
                    <ul class="list-unstyled">
                        <li>19 Posts</li>
                    </ul>
                </div>
            </div>
             <div class="top-author">
                <img src="images/news/author-02.jpg" alt="author-thumb">
                <div class="info">
                    <h4 class="name"><a href="author.html">Handson</a></h4>
                    <ul class="list-unstyled">
                        <li>18 Posts</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
				</div>
			</div>
		</div>
	</div>
</section>

@endsection