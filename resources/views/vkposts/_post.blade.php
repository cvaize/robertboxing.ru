<?php
/**
 * @var \App\Models\Media\Vk\VkPost $vkPost
 */
?>

<div class="{{ $vkPost->isPinned('post') }} col-xl-5 col-md-6 col">
	@if($vkPost->hasImages() || $vkPost->hasVideos())
		<div class="row">
			<div id="{{ $vkPost->getPostID() }}" class="col-12 post-images carousel slide" data-ride="carousel">
				<div class="carousel-inner">
					@foreach($vkPost->getFiles() as $file)
						@if ($loop->first)
							@if($file['type'] === 'link')
								<div class="carousel-item active">
									<div class="cover-block">
										<div class="cover-block-img" style="background-image: url('{{ $file['url'] }}');"></div>
									</div>
									<div class="original-block-img" onclick="window.open('{{ $vkPost->getLink() }}', '_blank')">
										<img src="{{ $file['url'] }}" alt="" class="">
									</div>
								</div>
							@elseif($file['type'] === 'video_vk')
								<div class="carousel-item active">
									<div class="video-image embed-responsive embed-responsive-16by9" onclick="window.open('{{ $file['url'] }}', '_blank')">
										<img src="{{ $file['photo'] }}" alt="" class="embed-responsive-item">
										<div class="video-thumb-play"></div>
									</div>
								</div>
							@elseif($file['type'] === 'video_youtube')
								<div class="carousel-item active">
									@if($file['is_blocked'])
										<div class="video-image youtube-blocked embed-responsive embed-responsive-16by9">
											<img src="{{ $file['photo'] }}" alt="" class="embed-responsive-item">
											<div class="video-thumb-play"></div>
										</div>
									@else
										<div class="video-image youtube embed-responsive embed-responsive-16by9">
											<img src="{{ $file['photo'] }}" alt="{{ $file['url'] }}" class="embed-responsive-item">
											<div class="video-thumb-play"></div>
										</div>
									@endif
								</div>
							@endif
						@else
							@if($file['type'] === 'link')
								<div class="carousel-item">
									<div class="cover-block">
										<div class="cover-block-img" style="background-image: url('{{ $file['url'] }}');"></div>
									</div>
									<div class="original-block-img" onclick="window.open('{{ $vkPost->getLink() }}', '_blank')">
										<img src="{{ $file['url'] }}" alt="" class="">
									</div>
								</div>
							@elseif($file['type'] === 'video_vk')
								<div class="carousel-item">
									<div class="video-image embed-responsive embed-responsive-16by9" onclick="window.open('{{ $file['url'] }}', '_blank')">
										<img src="{{ $file['photo'] }}" alt="" class="embed-responsive-item">
										<div class="video-thumb-play"></div>
									</div>
								</div>
							@elseif($file['type'] === 'video_youtube')
								<div class="carousel-item">
									@if($file['is_blocked'])
										<div class="video-image youtube-blocked embed-responsive embed-responsive-16by9">
											<img src="{{ $file['photo'] }}" alt="" class="embed-responsive-item">
											<div class="video-thumb-play"></div>
										</div>
									@else
										<div class="video-image youtube embed-responsive embed-responsive-16by9">
											<img src="{{ $file['photo'] }}" alt="{{ $file['url'] }}" class="embed-responsive-item">
											<div class="video-thumb-play"></div>
										</div>
									@endif
								</div>
							@endif
						@endif
					@endforeach
				</div>
				@if (count($vkPost->getFiles()) > 1)
					<a href="#{{ $vkPost->getPostID() }}" class="carousel-control-prev" role="button" data-slide="prev">
						<span class="carousel-control-prev-icon" aria-hidden="true"></span>
						<span class="sr-only">Previous</span>
					</a>
					<a href="#{{ $vkPost->getPostID() }}" class="carousel-control-next" role="button" data-slide="next">
						<span class="carousel-control-next-icon" aria-hidden="true"></span>
						<span class="sr-only">Next</span>
					</a>
				@endif
			</div>
		</div>
	@endif
	{{--<div class='row vk-post__date'>{{ $vkPost->getDate() }}</div>--}}
	<div class="row">
		<div class="col-12 post-body d-flex flex-column justify-content-between">
			<div class="post-text">
				@if($vkPost->hasText())
					<pre class="not-history">{!! $vkPost->getText() !!}</pre>
					@if($vkPost->hasCopyHistory())
						<pre class="history">{!! $vkPost->getCopyHistoryText() !!}</pre>
					@endif
				@else
					@if($vkPost->hasCopyHistory())
						<pre style="font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica Neue, Arial, sans-serif, Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol">{!! $vkPost->getCopyHistoryText() !!}</pre>
					@endif
				@endif
			</div>
		</div>
	</div>
	<div class="row button-to-post">
		<div class="col-4 col-md-5">
			<a class="btn btn-block" role="button" href="{{ $vkPost->getLink() }}" target="_blank">Читать в ВК</a>
		</div>
	</div>
	<div class='{{ $vkPost->isPinned('pin') }}'></div>
</div>