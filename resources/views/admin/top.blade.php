<x-app-layout title="TOP">
    <div class="row my-4">
        <div class="col-12">
            <div id="carouselExampleIndicators" class="carousel slide shadow">
                <div class="carousel-indicators">
                    @foreach($carousels as $carousel)
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $loop->index }}" aria-label="Slide {{ $loop->index }}" @if($loop->first) class="active" aria-current="true" @endif></button>
                    @endforeach
                </div>
                <div class="carousel-inner">
                    @foreach($carousels as $carousel)
                    <div class="carousel-item @if($loop->first) active @endif">
                        <a href="{{ route('admin.media.show', $carousel) }}">
                            <img src="{{ asset($carousel->image) }}" class="d-block w-100" alt="{{ $carousel->alt }}">
                        </a>
                    </div>    
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body">
                    <div class="card text-bg-primary mb-2">
                        <div class="card-body p-2">
                            <span class="d-block text-center">PICK UP</span>
                        </div>
                    </div>
                    @foreach($pickUps as $pickUp)
                    <div class="card text-bg-light mb-2">
                        <div class="card-body">
                            <a href="{{ route('admin.media.show', $pickUp) }}">
                                <img src="{{ asset($pickUp->image) }}" class="d-block w-100 h-auto" alt="{{ $carousel->alt }}">
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card shadow">
                <div class="card-body">
                    <div class="card text-bg-success mb-2">
                        <div class="card-body p-2">
                            <span class="d-block">お知らせ</span>
                        </div>
                    </div>
                    <div class="card mb-2">
                        <div class="card-body">
                            @foreach($news as $list)
                            <p class="mb-1">{{ $list->release_date->format('Y年m月d日') }}</p>
                            <p class="ms-4 mb-4">
                                <a href="{{ route('admin.news.show', $list) }}">
                                    {{ $list->title }}
                                    @if ($list->link_flg == $NewsConsts::LINK_FLG_ON)
                                        <x-blank />
                                    @endif
                                </a>
                            </p>
                            @endforeach
                            <a class="float-end" href="{{ route('admin.news.index') }}">
                                <p>過去のお知らせを見る</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
