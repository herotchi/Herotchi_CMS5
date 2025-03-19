<?php

namespace Tests\Feature\Admin\Product;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

use App\Models\Admin;
use App\Models\FirstCategory;
use App\Models\SecondCategory;
use App\Models\Tag;
use App\Models\Product;

use App\Consts\ProductConsts;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    private $_url;
    private $_admin;
    private $_firstCategories;
    private $_secondCategories;
    private $_tags;
    private $_product;
    private $_oldFileName;
    private $_newFileName;

    public function setUp(): void
    {
        parent::setUp();

        // ログイン用ユーザー
        $this->_admin = Admin::factory()->create([
            'login_id' => 'test1test1',
            'password' => Hash::make('test1test1'),
        ]);

        $this->_firstCategories = FirstCategory::factory(2)->create();

        $this->_secondCategories[0] = SecondCategory::factory()->create([
            'first_category_id' => $this->_firstCategories[0]->id,
        ]);
        $this->_secondCategories[1] = SecondCategory::factory()->create([
            'first_category_id' => $this->_firstCategories[1]->id,
        ]);

        $this->_tags = Tag::factory(5)->create();

        Storage::fake('public');
        $this->_oldFileName = 'test_product_image.png';
        // ダミーファイルを作成（実際には物理ファイルは作られない）
        $file = UploadedFile::fake()->image($this->_oldFileName);
        // ファイルを保存
        $file->storeAs(ProductConsts::IMAGE_FILE_DIR, $this->_oldFileName, 'public');
        $this->_product = Product::factory()->create([
            'first_category_id' => $this->_firstCategories[0]->id,
            'second_category_id' => $this->_secondCategories[0]->id,
            'name' => 'test1',
            'image' => 'storage/' . ProductConsts::IMAGE_FILE_DIR . '/' . $file->hashName(),
            'detail' => fake()->sentence(),
            'release_flg' => ProductConsts::RELEASE_FLG_ON,
        ]);

        $this->_product->tags()->attach([
            $this->_tags[0]->id,
            $this->_tags[1]->id,
            $this->_tags[2]->id,
        ]);

        // basic認証
        $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode('test1test1:test1test1'),
        ]);

        $this->_url = route('admin.product.destroy', $this->_product);
    }


    /** @test */
    public function レコード存在チェック(): void
    {
        $this->assertDatabaseHas('products', [
            'id' => $this->_product->id,
        ]);

        $this->assertDatabaseHas('product_tag', [
            'product_id' => $this->_product->id,
            'tag_id' => $this->_tags[0]->id
        ]);
        $this->assertDatabaseHas('product_tag', [
            'product_id' => $this->_product->id,
            'tag_id' => $this->_tags[1]->id
        ]);
        $this->assertDatabaseHas('product_tag', [
            'product_id' => $this->_product->id,
            'tag_id' => $this->_tags[2]->id
        ]);
    }


    /** @test */
    public function レコード削除処理(): void
    {
        $this->actingAs($this->_admin, 'admin');

         // 削除リクエスト
        $response = $this->delete(route('admin.product.destroy', $this->_product));

        // 削除後のリダイレクト確認
        $response->assertRedirect(route('admin.product.index'));
        // セッションにメッセージが存在するか確認
        $response->assertSessionHas('msg_success', '製品情報を削除しました。');

        // データベースからレコードが削除されているか確認
        $this->assertDatabaseMissing('products', [
            'id' => $this->_product->id,
        ]);

        // 紐づいているproduct_tagテーブルのレコードも削除されているか確認
        $this->assertDatabaseMissing('product_tag', [
            'product_id' => $this->_product->id,
            'tag_id' => $this->_tags[0]->id,
        ]);
        $this->assertDatabaseMissing('product_tag', [
            'product_id' => $this->_product->id,
            'tag_id' => $this->_tags[1]->id,
        ]);
        $this->assertDatabaseMissing('product_tag', [
            'product_id' => $this->_product->id,
            'tag_id' => $this->_tags[2]->id,
        ]);

        // 紐づいている画像ファイルも削除されているか確認
        Storage::disk('public')->assertMissing(str_replace('storage/', '', $this->_product->image));
    }
}
