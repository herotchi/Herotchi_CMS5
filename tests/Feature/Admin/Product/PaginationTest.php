<?php

namespace Tests\Feature\Admin\Product;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use App\Models\Admin;
use App\Models\FirstCategory;
use App\Models\SecondCategory;
use App\Models\Tag;
use App\Models\Product;

use App\Consts\ProductConsts;


class PaginationTest extends TestCase
{
    use RefreshDatabase;

    private $_url;
    private $_admin;
    private $_firstCategories;
    private $_secondCategories;
    private $_tags;
    private $_product;

    public function setUp(): void
    {
        parent::setUp();

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
        $fileName = 'test_product_image.png';
        // ダミーファイルを作成（実際には物理ファイルは作られない）
        $file = UploadedFile::fake()->image($fileName);
        // ファイルを保存してパスを取得
        $filePath = $file->storeAs(ProductConsts::IMAGE_FILE_DIR, $fileName, 'public');
        $this->_product[0] = Product::factory()->create([
            'first_category_id' => $this->_firstCategories[0]->id,
            'second_category_id' => $this->_secondCategories[0]->id,
            'name' => 'test1',
            'image' => $filePath,
            'detail' => fake()->sentence(),
            'release_flg' => ProductConsts::RELEASE_FLG_ON,
        ]);
        $this->_product[1] = Product::factory()->create([
            'first_category_id' => $this->_firstCategories[1]->id,
            'second_category_id' => $this->_secondCategories[1]->id,
            'name' => 'test2',
            'image' => $filePath,
            'detail' => fake()->sentence(),
            'release_flg' => ProductConsts::RELEASE_FLG_OFF,
        ]);
        $this->_product[2] = Product::factory()->create([
            'first_category_id' => $this->_firstCategories[1]->id,
            'second_category_id' => $this->_secondCategories[1]->id,
            'name' => 'aaaa1',
            'image' => $filePath,
            'detail' => fake()->sentence(),
            'release_flg' => ProductConsts::RELEASE_FLG_ON,
        ]);
        $this->_product[3] = Product::factory()->create([
            'first_category_id' => $this->_firstCategories[1]->id,
            'second_category_id' => $this->_secondCategories[1]->id,
            'name' => 'test3',
            'image' => $filePath,
            'detail' => fake()->sentence(),
            'release_flg' => ProductConsts::RELEASE_FLG_ON,
        ]);
        $this->_product[4] = Product::factory()->create([
            'first_category_id' => $this->_firstCategories[0]->id,
            'second_category_id' => $this->_secondCategories[0]->id,
            'name' => 'test4',
            'image' => $filePath,
            'detail' => fake()->sentence(),
            'release_flg' => ProductConsts::RELEASE_FLG_ON,
        ]);
        
        $this->_product[0]->tags()->attach([
            $this->_tags[0]->id,
            $this->_tags[1]->id,
            $this->_tags[2]->id,
        ]);
        $this->_product[1]->tags()->attach([
            $this->_tags[2]->id,
            $this->_tags[3]->id,
        ]);
        $this->_product[2]->tags()->attach([
            $this->_tags[1]->id,
        ]);
        $this->_product[3]->tags()->attach([
            $this->_tags[2]->id,
            $this->_tags[3]->id,
        ]);
        $this->_product[4]->tags()->attach([
            $this->_tags[2]->id,
            $this->_tags[3]->id,
        ]);

        // basic認証
        $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode('test1test1:test1test1'),
        ]);

        $this->_url = route('admin.product.index');
    }


    /** @test */
    public function ページネーションのテスト(): void
    {
        $this->actingAs($this->_admin, 'admin');

        $response = $this->get($this->_url);
        $response->assertOk(); // ステータスコード200を確認
        $response->assertSee('pagination'); // ページネーションのHTMLが表示されているか
        $this->assertCount(ProductConsts::ADMIN_PAGENATE_LIST_LIMIT, $response->viewData('lists')); // 1ページのデータ数が定数件か

        // 2ページ目
        $response = $this->get(route('admin.product.index', ['page' => 2]));
        $response->assertOk();
        $this->assertCount(ProductConsts::ADMIN_PAGENATE_LIST_LIMIT, $response->viewData('lists')); // 2ページ目のデータ数を確認

        // ページネーションのリンクが正しく生成されているか
        $response = $this->get(route('admin.product.index', ['page' => 1]));
        $response->assertOk();
        //$response->assertSee('?page=2', false); // 2ページ目へのリンクがあるか
        $response->assertSee('?page=2'); // 2ページ目へのリンクがあるか

        // 検索条件を指定したときにページネーションが維持されるか
        //$response = $this->get(route('admin.product.index', ['name' => 'test', 'page' => 2]));
        $response = $this->get(route('admin.product.index', ['name' => 'test', 'page' => 1]));
        $response->assertOk();
        $response->assertSee('?name=test&page=2'); // 検索条件付きのページネーションリンクがあるか

    }
}
