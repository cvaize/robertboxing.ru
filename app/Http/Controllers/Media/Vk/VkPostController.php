<?php

namespace App\Http\Controllers\Media\Vk;

use App\Http\Controllers\Controller;
use App\Models\Media\Vk\VkPost;
use Illuminate\Http\Request;

class VkPostController extends Controller {
	/**
	 * @var
	 */
	protected $vkPosts;

	/**
	 * VkPostController constructor.
	 * @param VkPost $vkPosts
	 */
	public function __construct(VkPost $vkPosts) {
		$this->vkPosts = $vkPosts;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$vkPosts = $this->vkPosts->posts()->get();
		return view('vkposts.index', compact('vkPosts'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  VkPost $vkPost
	 * @return \Illuminate\Http\Response
	 */
	public function show(VkPost $vkPost) {
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  VkPost $vkPost
	 * @return \Illuminate\Http\Response
	 */
	public function edit(VkPost $vkPost) {
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  VkPost                   $vkPost
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, VkPost $vkPost) {
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  VkPost $vkPost
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(VkPost $vkPost) {
		//
	}
}
