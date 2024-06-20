<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subitem;
use App\Models\Topic;
use Illuminate\Http\Request;


class SubitemController extends Controller
{
    public function index($topicId)
    {
        $subitems = Subitem::where('topic_id', $topicId)->get();
        return response()->json($subitems);
    }

    public function store(Request $request, $topicId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $topic = Topic::findOrFail($topicId);

        $subitem = $topic->subitems()->create([
            'title' => $request->title,
        ]);

        return response()->json($subitem, 201);
    }

    public function show($topicId, $id)
    {
        $subitem = Subitem::where('topic_id', $topicId)->findOrFail($id);
        return response()->json($subitem);
    }

    public function update(Request $request, $topicId, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $subitem = Subitem::where('topic_id', $topicId)->findOrFail($id);
        $subitem->update([
            'title' => $request->title,
        ]);

        return response()->json($subitem, 200);
    }

    public function destroy($topicId, $id)
    {
        $subitem = Subitem::where('topic_id', $topicId)->findOrFail($id);
        $subitem->delete();

        return response()->json(null, 204);
    }
}
