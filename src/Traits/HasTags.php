<?php

namespace YourVendor\CrmPackage\Traits;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use YourVendor\CrmPackage\Models\Tag;

/**
 * Trait HasTags
 * @package YourVendor\CrmPackage\Traits
 */
trait HasTags
{
    /**
     * Get all of the model's tags.
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable')->withTimestamps();
    }

    /**
     * Sync the given tags to the model.
     *
     * @param array|\Illuminate\Support\Collection|string $tags
     * @return void
     */
    public function syncTags($tags)
    {
        if (is_string($tags)) {
            $tags = collect(explode(',', $tags))->map(function ($tagName) {
                return trim($tagName);
            })->filter()->all();
        }

        $tagIds = collect($tags)->map(function ($tag) {
            if ($tag instanceof Tag) {
                return $tag->id;
            }
            // Find or create the tag by name (or slug)
            return Tag::firstOrCreate(["slug" => Str::slug($tag)], ["name" => $tag])->id;
        })->toArray();

        $this->tags()->sync($tagIds);
    }

    /**
     * Attach one or more tags to the model.
     *
     * @param array|\Illuminate\Support\Collection|string $tags
     * @return void
     */
    public function attachTags($tags)
    {
        if (is_string($tags)) {
            $tags = collect(explode(',', $tags))->map(function ($tagName) {
                return trim($tagName);
            })->filter()->all();
        }

        $tagIds = collect($tags)->map(function ($tag) {
            if ($tag instanceof Tag) {
                return $tag->id;
            }
            return Tag::firstOrCreate(["slug" => Str::slug($tag)], ["name" => $tag])->id;
        })->toArray();

        $this->tags()->attach($tagIds);
    }

    /**
     * Detach one or more tags from the model.
     *
     * @param array|\Illuminate\Support\Collection|string $tags
     * @return void
     */
    public function detachTags($tags = null)
    {
        if (is_null($tags)) {
            $this->tags()->detach();
            return;
        }

        if (is_string($tags)) {
            $tags = collect(explode(',', $tags))->map(function ($tagName) {
                return trim($tagName);
            })->filter()->all();
        }

        $tagIds = collect($tags)->map(function ($tag) {
            if ($tag instanceof Tag) {
                return $tag->id;
            }
            // Find tag by name (or slug) to get its ID for detaching
            $foundTag = Tag::where("slug", Str::slug($tag))->orWhere("name", $tag)->first();
            return $foundTag ? $foundTag->id : null;
        })->filter()->toArray();

        if (!empty($tagIds)) {
            $this->tags()->detach($tagIds);
        }
    }
}

