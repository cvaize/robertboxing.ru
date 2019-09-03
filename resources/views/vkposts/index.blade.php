<?php
/**
 * @var \App\Models\Media\Vk\VkPost $vkPost
 */
?>

@extends('layouts.app')

@section('posts')
	<div class="col-12">
		<div class="row justify-content-center">
			<div class="col-12 text-center gora-posts">
				<h2>События в Горе</h2>
			</div>
			<div class="vk-posts d-flex flex-column justify-content-center">
				@forelse($vkPosts as $vkPost)
					@include('vkposts._post', [
						'post' => $vkPost
					])
				@empty

				@endforelse
			</div>

			<div class="modal fade" id="modal-video" tabindex="-1" role="dialog" aria-labelledby="modal-video-label">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="modal-video">
								<div class="embed-responsive embed-responsive-16by9">
									<iframe class="embed-responsive-item" src="" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop