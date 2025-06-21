<?php

namespace App\Http\Controllers;

use App\Models\ListTask;
use App\Models\Project;
use Illuminate\Http\Request;

class ListTaskController extends Controller
{
    public function create(Request $request, Project $projet)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $lastOrder = ListTask::where('project_id', $projet->id)->max('order');
        $newOrder = $lastOrder ? $lastOrder + 1 : 1;

        $listTask = ListTask::create([
            'title' => $request->input('title'),
            'order' => $newOrder,
            'project_id' => $projet->id,
        ]);

        $html = view('components.block-list-task', compact('listTask'))->render();
        return response()->json(['html' => $html]);
    }

   
    public function updateOrder(Request $request)
    {

        $request->validate([
            'orderList' => 'required|array',
        ]);


        $orderData = $request->input('orderList');

        foreach ($orderData as $item) {
            ListTask::where('id', $item['listTaskId'])
                ->update(['order' => $item['order']]);
        }


        return response()->json([
            'message' => "ok",
            // 'newOrder' => $request->input('newOrder'),
        ]);
    }
}
