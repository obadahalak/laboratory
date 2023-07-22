<?php

namespace App\Http\Trait;

use App\Models\Section;

trait sortingSection{

    public function moveUpItem($itemId,  $guard){

        $section = Section::find($itemId);

        if($section->index==1)return response()->json(['error'=>'the section is alraedy the first element'],422);

        $previosElement = $guard=='admin' ?  Section::where('account_id',null)->where('index',$section->index - 1)->first()
            : auth('lab')->user()->Section->where('index',$section->index - 1)->first();

        $section->update(['index' => $section->index  - 1]);

        $previosElement->update(['index' => $section->index + 1]);

        return response()->json(['message'=>'updated successfully']);
    }
    public function moveDownItem($itemId,$guard){

        $section = Section::find($itemId);

        if($section->index >=  Section::where('account_id',$guard)->orderBy('index','desc')->first()->index)return response()->json(['error'=>'the section is alraedy the last element'],422);

        $nextElement =auth('lab')->user()->Section->where('index',$section->index + 1)->first();

        $section->update(['index' => $section->index  + 1]);

        $nextElement->update(['index' => $section->index - 1]);

        return response()->json(['message'=>'updated successfully']);
    }


}
