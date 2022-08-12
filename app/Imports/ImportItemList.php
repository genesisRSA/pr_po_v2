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

        $checkIfRelated = CategoryItem::where('category_name',$row['category'])->first();

        $catName = isset(CategoryItem::where('category_name',$row['category'])->first()->id) ? CategoryItem::where('category_name',$row['category'])->first()->id : 'not okay';
        $subCatName = isset(SubCategoryItem::where('subcategory_name',$row['subcategory'])->first()->id) ? SubCategoryItem::where('subcategory_name',$row['subcategory'])->first()->id : 'not okay';  

        // if(CategoryItem::where('category_name',$row['category'])->get()->count() == 0 || SubCategoryItem::where('subcategory_name',$row['subcategory'])->get()->count() == 0){
        //     $detection+=1;
        // }

        ///// the row must not be push through if the category doesn't have a subcategory item as requested (vice-versa)
        
        if(isset($checkIfRelated)){

            if(!$checkIfRelated->subcategory_items->pluck('subcategory_name')->contains($row['subcategory'])){
                $detection+=1;
            } 
       }

       if($row['item_code']!=null){
            foreach(ItemList::all() as $item){
                if(in_array(strtolower(trim($row['item_code'])), [ strtolower($item->item_code) ])){
                    $detection+=1;
                }
            }
       } else {
            $detection+=1;
       }

    //    if($row['validity_date'] == null){
    //         $detection+=1;
    //    }

    //    $d = \DateTime::createFromFormat('Y-m-d', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(trim($row['validity_date']))->format('Y-m-d'));
  
    //    if(($d && $d->format('Y-m-d') === \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(trim($row['validity_date']))->format('Y-m-d')) == true){

    //    } else {
    //          $detection+=1;
    //    }

            if($catName!='not okay' && $subCatName!='not okay'){
                foreach($item_lists as $item){
                    if(    in_array($catName, [$item->category_item_id]) &&
                           in_array($subCatName, [$item->sub_category_item_id]) &&
                           in_array(strtolower(trim($row['part_name'])), [strtolower($item->part_name)]) &&
                           in_array(strtolower(trim($row['material'])), [strtolower($item->material)]) &&
                           in_array(strtolower(trim($row['dimension'])), [strtolower($item->dimension)])
                           ){
                            $detection+=1;
                       }
                }
            } else {
                $detection +=1;
            }
    


        if ($detection == 0) 
        {
            return new ItemList([
                'item_code' => trim($row['item_code']),
                'category_item_id' => CategoryItem::where('category_name',$row['category'])->first()->id,
                'sub_category_item_id' => SubCategoryItem::where('subcategory_name',$row['subcategory'])->first()->id,
                'part_name' => trim($row['part_name']),
                'material' => trim($row['material']),
                'dimension' => trim($row['dimension']),
                'unit_price' => trim('₱ '.number_format($row['unit_price'],2, '.', ',')),
                'validity_date' => $row['validity_date'] == null ? null : \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(trim($row['validity_date']))->format('Y-m-d'),
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
