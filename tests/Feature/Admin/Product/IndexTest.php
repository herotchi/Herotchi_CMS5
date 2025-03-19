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



class IndexTest extends TestCase
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
    public function 画面のアクセス制御(): void
    {
        $this->actingAs($this->_admin, 'admin');
        $this->get($this->_url)->assertOk();
    }

    /** @test */
    public function 検索条件なしで全てのレコードが表示される(): void
    {
        $this->actingAs($this->_admin, 'admin');
        // 検索条件なしでGETリクエスト
        $response = $this->get($this->_url);
        
        // ステータスコードが200であることを確認
        $response->assertOk();

        // すべてのレコードが表示されていることを確認
        foreach ($this->_product as $product) {
            $response->assertSee($product->name);
        }
    }

    /** @test */
    public function 製品名に一致するレコードのみが表示される(): void
    {
        $this->actingAs($this->_admin, 'admin');
        
        // 完全一致
        $response = $this->get(route('admin.product.index', ['name' => 'test1']));
        $response->assertOk();
        $response->assertSee($this->_product[0]->name);
        $response->assertDontSee($this->_product[1]->name);
        $response->assertDontSee($this->_product[2]->name);

        // 部分一致
        $response = $this->get(route('admin.product.index', ['name' => 'est']));
        $response->assertOk();
        $response->assertSee($this->_product[0]->name);
        $response->assertSee($this->_product[1]->name);
        $response->assertDontSee($this->_product[2]->name);

        // 前方一致
        $response = $this->get(route('admin.product.index', ['name' => 'aa']));
        $response->assertOk();
        $response->assertSee($this->_product[2]->name);
        $response->assertDontSee($this->_product[0]->name);
        $response->assertDontSee($this->_product[1]->name);

        // 後方一致
        $response = $this->get(route('admin.product.index', ['name' => '1']));
        $response->assertOk();
        $response->assertSee($this->_product[0]->name);
        $response->assertSee($this->_product[2]->name);
        $response->assertDontSee($this->_product[1]->name);

        // 不一致
        $response = $this->get(route('admin.product.index', ['name' => 'bcd']));
        $response->assertOk();
        foreach ($this->_product as $product) {
            $response->assertDontSee($product->name);
        }
    }


    /** @test */
    public function 大カテゴリに一致するレコードのみが表示される(): void
    {
        $this->actingAs($this->_admin, 'admin');
        
        // HIT
        $response = $this->get(route('admin.product.index', ['first_category_id' => $this->_firstCategories[0]->id]));
        $response->assertOk();
        $response->assertSee($this->_product[0]->name);
        $response->assertDontSee($this->_product[1]->name);
        $response->assertDontSee($this->_product[2]->name);
    }


    /** @test */
    public function 中カテゴリに一致するレコードのみが表示される(): void
    {
        $this->actingAs($this->_admin, 'admin');
        
        // HIT
        $response = $this->get(route('admin.product.index', [
            'first_category_id' => $this->_firstCategories[1]->id,
            'second_category_id' => $this->_secondCategories[1]->id
        ]));
        $response->assertOk();
        $response->assertSee($this->_product[1]->name);
        $response->assertSee($this->_product[2]->name);
        $response->assertDontSee($this->_product[0]->name);
    }


    /** @test */
    public function タグに一致するレコードのみが表示される(): void
    {
        $this->actingAs($this->_admin, 'admin');
        
        // 単体HIT
        $response = $this->get(route('admin.product.index', [
            'tag_ids' => [
                $this->_tags[1]->id,
            ]
        ]));
        $response->assertOk();
        $response->assertSee($this->_product[0]->name);
        $response->assertSee($this->_product[2]->name);
        $response->assertDontSee($this->_product[1]->name);

        // 複数HIT
        $response = $this->get(route('admin.product.index', [
            'tag_ids' => [
                $this->_tags[1]->id,
                $this->_tags[2]->id,
            ]
        ]));
        $response->assertOk();
        $response->assertSee($this->_product[0]->name);
        $response->assertDontSee($this->_product[1]->name);
        $response->assertDontSee($this->_product[2]->name);

        // 複数HIT
        $response = $this->get(route('admin.product.index', [
            'tag_ids' => [
                $this->_tags[0]->id,
                $this->_tags[1]->id,
                $this->_tags[2]->id,
            ]
        ]));
        $response->assertOk();
        $response->assertSee($this->_product[0]->name);
        $response->assertDontSee($this->_product[1]->name);
        $response->assertDontSee($this->_product[2]->name);
    }

    /** @test */
    public function 表示設定に一致するレコードのみが表示される(): void
    {
        $this->actingAs($this->_admin, 'admin');

        // 検索条件
        $response = $this->get(route('admin.product.index', [
            'release_flg' => [
                ProductConsts::RELEASE_FLG_ON
            ]
        ]));
        $response->assertOk();
        $response->assertSee($this->_product[0]->name);
        $response->assertSee($this->_product[2]->name);
        $response->assertDontSee($this->_product[1]->name);

        // 非表示
        $response = $this->get(route('admin.product.index', [
            'release_flg' => [
                ProductConsts::RELEASE_FLG_OFF
            ]
        ]));
        $response->assertOk();
        $response->assertSee($this->_product[1]->name);
        $response->assertDontSee($this->_product[0]->name);
        $response->assertDontSee($this->_product[2]->name);

        // 両方
        $response = $this->get(route('admin.product.index', [
            'release_flg' => [
                ProductConsts::RELEASE_FLG_ON, 
                ProductConsts::RELEASE_FLG_OFF
            ]
        ]));
        $response->assertOk();
        $response->assertSee($this->_product[0]->name);
        $response->assertSee($this->_product[1]->name);
        $response->assertSee($this->_product[2]->name);
    }


    /** @test */
    public function 複数の検索条件(): void
    {
        $this->actingAs($this->_admin, 'admin');
        
        $response = $this->get(route('admin.product.index', [
            'first_category_id' => $this->_firstCategories[0]->id,
            'second_category_id' => $this->_secondCategories[0]->id,
            'tag_ids' => [
                $this->_tags[0]->id,
                $this->_tags[1]->id,
            ],
            'name' => 'test',
            'release_flg' => [
                ProductConsts::RELEASE_FLG_ON
            ]
        ]));

        $response->assertOk();
        $response->assertSee($this->_product[0]->name);
        $response->assertDontSee($this->_product[1]->name);
        $response->assertDontSee($this->_product[2]->name);
    }


    /** @test */
    public function バリデーション(): void
    {
        $this->actingAs($this->_admin, 'admin');

        // 大カテゴリ
        // リダイレクト
        $response = $this->from(route('admin.product.index'))->get(route('admin.product.index', ['first_category_id' => 'a']));
        $response->assertRedirect(route('admin.product.index'));
        // 整数以外
        $this->get(route('admin.product.index', ['first_category_id' => 'a']))->assertInvalid(['first_category_id' => '大カテゴリ名には、整数']);
        $this->get(route('admin.product.index', ['first_category_id' => $this->_firstCategories[0]->id]))->assertValid();
        // DBに存在しない
        $this->get(route('admin.product.index', ['first_category_id' => 100]))->assertInvalid(['first_category_id' => '大カテゴリ名は、有効']);
        $this->get(route('admin.product.index', ['first_category_id' => $this->_firstCategories[0]->id]))->assertValid();

        // 中カテゴリ
        // リダイレクト
        $response = $this->from(route('admin.product.index'))->get(route('admin.product.index', ['second_category_id' => 'a']));
        $response->assertRedirect(route('admin.product.index'));
        // 整数以外
        $this->get(route('admin.product.index', ['second_category_id' => 'a']))->assertInvalid(['second_category_id' => '中カテゴリ名には、整数']);
        $this->get(route('admin.product.index', ['second_category_id' => $this->_secondCategories[0]->id]))->assertValid();
        // DBに存在しない
        $this->get(route('admin.product.index', ['second_category_id' => 100]))->assertInvalid(['second_category_id' => '中カテゴリ名は、有効']);
        $this->get(route('admin.product.index', ['second_category_id' => $this->_secondCategories[0]->id]))->assertValid();
        // 大カテゴリと紐づいていない
        $this->get(route('admin.product.index', [
            'first_category_id' => $this->_firstCategories[0]->id,
            'second_category_id' => $this->_secondCategories[1]->id,
        ]))->assertInvalid(['second_category_id' => '大カテゴリと紐づいていない中カテゴリ']);
        $this->get(route('admin.product.index', [
            'first_category_id' => $this->_firstCategories[0]->id,
            'second_category_id' => $this->_secondCategories[0]->id,
        ]))->assertValid();

        // タグ
        // リダイレクト
        $response = $this->from(route('admin.product.index'))->get(route('admin.product.index', ['tag_ids' => 'a']));
        $response->assertRedirect(route('admin.product.index'));
        // 配列でない
        $this->get(route('admin.product.index', ['tag_ids' => $this->_tags[0]->id]))->assertInvalid(['tag_ids' => 'タグには、配列']);
        $this->get(route('admin.product.index', ['tag_ids' => [$this->_tags[0]->id]]))->assertValid();
        // 整数以外
        $this->get(route('admin.product.index', ['tag_ids' => ['a']]))->assertInvalid(['tag_ids.0' => 'タグには、整数']);
        $this->get(route('admin.product.index', ['tag_ids' => [$this->_tags[0]->id]]))->assertValid();
        // DBに存在しない
        $this->get(route('admin.product.index', ['tag_ids' => [100]]))->assertInvalid(['tag_ids.0' => 'タグは、有効']);
        $this->get(route('admin.product.index', ['tag_ids' => [$this->_tags[0]->id]]))->assertValid();

        // 製品名
        // リダイレクト
        $response = $this->from(route('admin.product.index'))->get(route('admin.product.index', ['name' => str_repeat('a', ProductConsts::NAME_LENGTH_MAX + 1)]));
        $response->assertRedirect(route('admin.product.index'));
        // 文字列でない
        $this->get(route('admin.product.index', ['name' => ['a']]))->assertInvalid(['name' => '製品名には、文字列']);
        $this->get(route('admin.product.index', ['name' => 'a']))->assertValid();
        // 51文字以上
        $this->get(route('admin.product.index', ['name' => str_repeat('a', ProductConsts::NAME_LENGTH_MAX + 1)]))->assertInvalid(['name' => '製品名の文字数は、' . ProductConsts::NAME_LENGTH_MAX . '文字以下']);
        $this->get(route('admin.product.index', ['name' => str_repeat('a', ProductConsts::NAME_LENGTH_MAX)]))->assertValid();

        // 表示設定
        // リダイレクト
        $response = $this->from(route('admin.product.index'))->get(route('admin.product.index', ['release_flg' => 'a']));
        $response->assertRedirect(route('admin.product.index'));
        // 配列でない
        $this->get(route('admin.product.index', ['release_flg' => ProductConsts::RELEASE_FLG_ON]))->assertInvalid(['release_flg' => '表示設定には、配列']);
        $this->get(route('admin.product.index', ['release_flg' => [ProductConsts::RELEASE_FLG_ON]]))->assertValid();
        // リストに存在しない
        $this->get(route('admin.product.index', ['release_flg' => [3]]))->assertInvalid(['release_flg.0' => '表示設定は、有効']);
        $this->get(route('admin.product.index', ['release_flg' => [ProductConsts::RELEASE_FLG_ON]]))->assertValid();
    }
}
