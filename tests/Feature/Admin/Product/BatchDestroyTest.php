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

class BatchDestroyTest extends TestCase
{
    use RefreshDatabase;

    private $_url;
    private $_admin;
    private $_firstCategories;
    private $_secondCategories;
    private $_tags;
    private $_products;
    private $_fileNames;

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
        $this->_fileNames[0] = 'test_product_image1.png';
        $this->_fileNames[1] = 'test_product_image2.png';
        $this->_fileNames[2] = 'test_product_image3.png';
        // ダミーファイルを作成（実際には物理ファイルは作られない）
        $file = UploadedFile::fake()->image($this->_fileNames[0]);
        // ファイルを保存
        $file->storeAs(ProductConsts::IMAGE_FILE_DIR, $this->_fileNames[0], 'public');
        $this->_products[0] = Product::factory()->create([
            'first_category_id' => $this->_firstCategories[0]->id,
            'second_category_id' => $this->_secondCategories[0]->id,
            'name' => 'test1',
            'image' => 'storage/' . ProductConsts::IMAGE_FILE_DIR . '/' . $file->hashName(),
            'detail' => fake()->sentence(),
            'release_flg' => ProductConsts::RELEASE_FLG_ON,
        ]);
        $file = UploadedFile::fake()->image($this->_fileNames[1]);
        $file->storeAs(ProductConsts::IMAGE_FILE_DIR, $this->_fileNames[1], 'public');
        $this->_products[1] = Product::factory()->create([
            'first_category_id' => $this->_firstCategories[1]->id,
            'second_category_id' => $this->_secondCategories[1]->id,
            'name' => 'test1',
            'image' => 'storage/' . ProductConsts::IMAGE_FILE_DIR . '/' . $file->hashName(),
            'detail' => fake()->sentence(),
            'release_flg' => ProductConsts::RELEASE_FLG_ON,
        ]);
        $file = UploadedFile::fake()->image($this->_fileNames[2]);
        $file->storeAs(ProductConsts::IMAGE_FILE_DIR, $this->_fileNames[2], 'public');
        $this->_products[2] = Product::factory()->create([
            'first_category_id' => $this->_firstCategories[1]->id,
            'second_category_id' => $this->_secondCategories[1]->id,
            'name' => 'test1',
            'image' => 'storage/' . ProductConsts::IMAGE_FILE_DIR . '/' . $file->hashName(),
            'detail' => fake()->sentence(),
            'release_flg' => ProductConsts::RELEASE_FLG_ON,
        ]);


        $this->_products[0]->tags()->attach([
            $this->_tags[0]->id,
            $this->_tags[1]->id,
            $this->_tags[2]->id,
        ]);
        $this->_products[1]->tags()->attach([
            $this->_tags[3]->id,
            $this->_tags[4]->id,
        ]);

        // basic認証
        $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode('test1test1:test1test1'),
        ]);

        $this->_url = route('admin.product.batch_destroy');
    }


    /** @test */
    public function レコード存在チェック(): void
    {
        $this->assertDatabaseHas('products', [
            'id' => $this->_products[0]->id,
        ]);
        $this->assertDatabaseHas('product_tag', [
            'product_id' => $this->_products[0]->id,
            'tag_id' => $this->_tags[0]->id
        ]);
        $this->assertDatabaseHas('product_tag', [
            'product_id' => $this->_products[0]->id,
            'tag_id' => $this->_tags[1]->id
        ]);
        $this->assertDatabaseHas('product_tag', [
            'product_id' => $this->_products[0]->id,
            'tag_id' => $this->_tags[2]->id
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $this->_products[1]->id,
        ]);
        $this->assertDatabaseHas('product_tag', [
            'product_id' => $this->_products[1]->id,
            'tag_id' => $this->_tags[3]->id
        ]);
        $this->assertDatabaseHas('product_tag', [
            'product_id' => $this->_products[1]->id,
            'tag_id' => $this->_tags[4]->id
        ]);
    }


    /** @test */
    public function バリデーション(): void
    {
        $this->actingAs($this->_admin, 'admin');

        // リダイレクト
        $this->from(route('admin.product.index'))
            ->delete($this->_url, ['delete_ids' => ''])
            ->assertRedirect(route('admin.product.index'));

        // 製品情報ID
        // 空
        $this->delete($this->_url, ['delete_ids' => ''])->assertInvalid(['delete_ids' => '製品情報は必須']);
        // 配列ではない
        $this->delete($this->_url, ['delete_ids' => 'a'])->assertInvalid(['delete_ids' => '製品情報には、配列']);
        // 整数ではない
        $this->delete($this->_url, ['delete_ids' => ['a']])->assertInvalid(['delete_ids.0' => '製品情報には、整数']);
        // DBに存在しない
        $this->delete($this->_url, ['delete_ids' => [100]])->assertInvalid(['delete_ids.0' => '製品情報は、有効では']);
        // 正常
        $this->delete($this->_url, ['delete_ids' => [$this->_products[2]->id]])->assertValid('delete_ids');
        // 複数正常
        $this->delete($this->_url, ['delete_ids' => [$this->_products[0]->id, $this->_products[1]->id]])->assertValid('delete_ids');
    }

    /** @test */
    public function メッセージ表示(): void
    {
        $this->actingAs($this->_admin, 'admin');

        // 製品情報ID
        // 空
        $this->delete($this->_url, ['delete_ids' => ''])->assertSessionHas('msg_failure', '製品情報は必須項目です。');
        // 配列ではない
        $this->delete($this->_url, ['delete_ids' => 'a'])->assertSessionHas('msg_failure', '製品情報には、配列を指定してください。');
        // 整数ではない
        $this->delete($this->_url, ['delete_ids' => ['a']])->assertSessionHas('msg_failure', '製品情報には、整数を指定してください。');
        // DBに存在しない
        $this->delete($this->_url, ['delete_ids' => [100]])->assertSessionHas('msg_failure', '選択された製品情報は、有効ではありません。');
        // 正常
        $this->delete($this->_url, ['delete_ids' => [$this->_products[2]->id]])->assertSessionHas('msg_success', '製品情報を一括削除しました。');
        // 複数正常
        $this->delete($this->_url, ['delete_ids' => [$this->_products[0]->id, $this->_products[1]->id]])->assertSessionHas('msg_success', '製品情報を一括削除しました。');
    }


    /** @test */
    public function レコード削除処理(): void
    {
        $this->actingAs($this->_admin, 'admin');

         // 削除リクエスト
        $response = $this->delete($this->_url, [
            'delete_ids' => [
                $this->_products[0]->id,
                $this->_products[1]->id
            ]
        ]);

        // 削除後のリダイレクト確認
        $response->assertRedirect(route('admin.product.index'));
        // セッションにメッセージが存在するか確認
        $response->assertSessionHas('msg_success', '製品情報を一括削除しました。');

        // データベースからレコードが削除されているか確認
        $this->assertDatabaseMissing('products', [
            'id' => $this->_products[0]->id,
        ]);
        $this->assertDatabaseMissing('products', [
            'id' => $this->_products[1]->id,
        ]);

        // 紐づいているproduct_tagテーブルのレコードも削除されているか確認
        $this->assertDatabaseMissing('product_tag', [
            'product_id' => $this->_products[0]->id,
            'tag_id' => $this->_tags[0]->id,
        ]);
        $this->assertDatabaseMissing('product_tag', [
            'product_id' => $this->_products[0]->id,
            'tag_id' => $this->_tags[1]->id,
        ]);
        $this->assertDatabaseMissing('product_tag', [
            'product_id' => $this->_products[0]->id,
            'tag_id' => $this->_tags[2]->id,
        ]);
        $this->assertDatabaseMissing('product_tag', [
            'product_id' => $this->_products[1]->id,
            'tag_id' => $this->_tags[3]->id,
        ]);
        $this->assertDatabaseMissing('product_tag', [
            'product_id' => $this->_products[1]->id,
            'tag_id' => $this->_tags[4]->id,
        ]);

        // 紐づいている画像ファイルも削除されているか確認
        Storage::disk('public')->assertMissing(str_replace('storage/', '', $this->_products[0]->image));
        Storage::disk('public')->assertMissing(str_replace('storage/', '', $this->_products[1]->image));
    }
}
