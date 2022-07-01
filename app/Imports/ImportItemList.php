<?php

namespace App\Imports;

use DB;
use App\Models\ItemList;
use App\Models\CategoryItem;
use App\Models\SubCategoryItem;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ImportItemList implements ToModel, SkipsOnError, WithHeadingRow, SkipsEmptyRows, WithChunkReading
{
    use Importable;

    public function model(array $row)
    {
        $detection = 0;

        $item_lists = DB::table('item_lists')->get();

        if(CategoryItem::where('category_name',$row['category'])->get()->count() == 0 || SubCategoryItem::where('subcategory_name',$row['subcategory'])->get()->count() == 0){
            $detection+=1;
        }


            $catName = isset(CategoryItem::where('category_name',$row['category'])->first()->id) ? CategoryItem::where('category_name',$row['category'])->first()->id : 'not okay';
            $subCatName = isset(SubCategoryItem::where('subcategory_name',$row['subcategory'])->first()->id) ? SubCategoryItem::where('subcategory_name',$row['subcategory'])->first()->id : 'not okay';  

            if($catName!='not okay' && $subCatName!='not okay'){
                foreach($item_lists as $item){
                    if(in_array($catName, [$item->category_item_id]) &&
                       in_array($subCatName, [$item->sub_category_item_id]) &&
                       in_array($row['part_name'], [$item->part_name]) &&
                       in_array($row['material'], [$item->material]) &&
                       in_array($row['dimension'], [$item->dimension])){
                            $detection+=1;
                       }
                }
            } else {
                $detection +=1;
            }


        if ($detection == 0) 
        {
            return new ItemList([
                'category_item_id' => CategoryItem::where('category_name',$row['category'])->first()->id,
                'sub_category_item_id' => SubCategoryItem::where('subcategory_name',$row['subcategory'])->first()->id,
                'part_name' => $row['part_name'],
                'material' => $row['material'],
                'dimension' => $row['dimension'],
                'unit_price' => '₱ '.number_format($row['unit_price'],2, '.', ','),
            ]);
        } else null;
    }

    public function onError(\Throwable $e)
    {
        
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
