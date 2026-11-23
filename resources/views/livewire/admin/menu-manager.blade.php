<div>
    <div class="grid2">
        {{-- Categories --}}
        <div class="card">
            <div class="head">
                <h2>Категории</h2>
                <button class="btn sm primary" wire:click="newCategory">+ Категория</button>
            </div>
            <div class="cats">
                @forelse ($this->categories as $c)
                    <div wire:key="cat-{{ $c->id }}"
                         class="cat {{ $c->id === $categoryId ? 'on' : '' }} {{ $c->is_active ? '' : 'off' }}"
                         wire:click="selectCategory({{ $c->id }})">
                        <span class="n">{{ $c->name_ru }}</span>
                        <span class="c">{{ $c->items_count }}</span>
                    </div>
                @empty
                    <div class="empty">Пока нет категорий</div>
                @endforelse
            </div>
        </div>

        {{-- Items of the selected category --}}
        <div class="card">
            @if ($this->current)
                @php($c = $this->current)
                <div class="head">
                    <div>
                        <h2>{{ $c->name_ru }}
                            @unless ($c->is_active)<span class="tag gray">скрыта</span>@endunless
                        </h2>
                        <div class="muted" style="font-size:12.5px">{{ $c->name_hy }} · {{ $c->name_en }}</div>
                    </div>
                    <div class="acts">
                        <button class="btn sm icon" title="Выше" wire:click="moveCategory({{ $c->id }}, -1)">↑</button>
                        <button class="btn sm icon" title="Ниже" wire:click="moveCategory({{ $c->id }}, 1)">↓</button>
                        <button class="btn sm" wire:click="toggleCategory({{ $c->id }})">{{ $c->is_active ? 'Скрыть' : 'Показать' }}</button>
                        <button class="btn sm" wire:click="editCategory({{ $c->id }})">Изменить</button>
                        <button class="btn sm primary" wire:click="newItem">+ Блюдо</button>
                    </div>
                </div>

                @forelse ($this->items as $it)
                    <div class="row {{ $it->is_active ? '' : 'off' }}" wire:key="item-{{ $it->id }}">
                        @if ($it->image_id)
                            <img class="thumb" src="{{ $it->imageUrl() }}" alt="" loading="lazy">
                        @else
                            <div class="thumb"></div>
                        @endif
                        <div class="t">
                            <b>{{ $it->name_ru }}
                                @if ($it->is_hit)<span class="tag">ХИТ</span>@endif
                                @unless ($it->is_active)<span class="tag gray">скрыто</span>@endunless
                            </b>
                            <span>{{ $it->name_hy }}{{ $it->desc_ru ? ' · '.\Illuminate\Support\Str::limit($it->desc_ru, 60) : '' }}</span>
                        </div>
                        <div class="price">
                            @if ($it->sizes->count())
                                {{ $it->sizes->map(fn ($s) => $s->label.': '.number_format($s->price, 0, '', ' '))->join(' / ') }}
                            @else
                                {{ number_format((int) $it->price, 0, '', ' ') }} ֏
                            @endif
                        </div>
                        <div class="acts">
                            <button class="btn sm icon" title="Выше" wire:click="moveItem({{ $it->id }}, -1)">↑</button>
                            <button class="btn sm icon" title="Ниже" wire:click="moveItem({{ $it->id }}, 1)">↓</button>
                            <button class="btn sm icon" title="{{ $it->is_active ? 'Скрыть' : 'Показать' }}" wire:click="toggleItem({{ $it->id }})">{{ $it->is_active ? '👁' : '🚫' }}</button>
                            <button class="btn sm" wire:click="editItem({{ $it->id }})">Изменить</button>
                        </div>
                    </div>
                @empty
                    <div class="empty">В этой категории пока нет блюд</div>
                @endforelse
            @else
                <div class="empty">Создайте первую категорию</div>
            @endif
        </div>
    </div>

    {{-- Category modal --}}
    @if ($showCategory)
        <div class="modal-bg" wire:click.self="$set('showCategory', false)">
            <form class="modal" wire:submit="saveCategory" style="width:min(480px,100%)">
                <div class="mh"><h2>{{ $cat['id'] ? 'Категория' : 'Новая категория' }}</h2>
                    <button type="button" class="btn sm" wire:click="$set('showCategory', false)">×</button></div>
                <div class="mb">
                    <label class="f"><span>Название (RU) *</span><input class="inp" wire:model="cat.name_ru">
                        @error('cat.name_ru')<div class="err">{{ $message }}</div>@enderror</label>
                    <label class="f"><span>Название (ՀԱՅ)</span><input class="inp" wire:model="cat.name_hy"></label>
                    <label class="f"><span>Название (EN)</span><input class="inp" wire:model="cat.name_en"></label>
                    <label class="check"><input type="checkbox" wire:model="cat.is_active"> Показывать в меню</label>
                </div>
                <div class="mf">
                    @if ($cat['id'])
                        <button type="button" class="btn danger" style="margin-right:auto"
                                wire:click="deleteCategory({{ $cat['id'] }})"
                                wire:confirm="Удалить категорию вместе со всеми блюдами?">Удалить</button>
                    @endif
                    <button type="button" class="btn" wire:click="$set('showCategory', false)">Отмена</button>
                    <button class="btn primary">Сохранить</button>
                </div>
            </form>
        </div>
    @endif

    {{-- Item modal --}}
    @if ($showItem)
        <div class="modal-bg" wire:click.self="$set('showItem', false)">
            <form class="modal" wire:submit="saveItem">
                <div class="mh"><h2>{{ $item['id'] ? 'Блюдо' : 'Новое блюдо' }}</h2>
                    <button type="button" class="btn sm" wire:click="$set('showItem', false)">×</button></div>
                <div class="mb">
                    {{-- Photo: resized in the browser, then sent as a data URL --}}
                    <div class="photo" x-data="{ busy: false }">
                        @if ($item['photo'])
                            <img src="{{ $item['photo'] }}" alt="">
                        @elseif ($item['image_url'])
                            <img src="{{ $item['image_url'] }}" alt="">
                        @else
                            <div class="ph"></div>
                        @endif
                        <div>
                            <label class="btn sm dark" style="cursor:pointer">
                                <span x-text="busy ? 'Обработка…' : 'Загрузить фото'"></span>
                                <input type="file" accept="image/*" hidden
                                       x-on:change="if(!$event.target.files[0]) return; busy = true;
                                           resizePhoto($event.target.files[0])
                                             .then(u => $wire.set('item.photo', u))
                                             .catch(() => alert('Не удалось открыть изображение'))
                                             .finally(() => { busy = false; $event.target.value = '' })">
                            </label>
                            @if ($item['photo'] || $item['image_url'])
                                <button type="button" class="btn sm" wire:click="removePhoto">Убрать фото</button>
                            @endif
                            <div class="muted" style="font-size:12px;margin-top:6px">Фото автоматически уменьшается до 1000 px.</div>
                            @error('item.photo')<div class="err">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="sec">Название</div>
                    <div class="cols3">
                        <label class="f"><span>RU *</span><input class="inp" wire:model="item.name_ru">
                            @error('item.name_ru')<div class="err">{{ $message }}</div>@enderror</label>
                        <label class="f"><span>ՀԱՅ</span><input class="inp" wire:model="item.name_hy"></label>
                        <label class="f"><span>EN</span><input class="inp" wire:model="item.name_en"></label>
                    </div>

                    <div class="sec">Описание</div>
                    <div class="cols3">
                        <label class="f"><span>RU</span><textarea class="inp" wire:model="item.desc_ru"></textarea></label>
                        <label class="f"><span>ՀԱՅ</span><textarea class="inp" wire:model="item.desc_hy"></textarea></label>
                        <label class="f"><span>EN</span><textarea class="inp" wire:model="item.desc_en"></textarea></label>
                    </div>

                    <div class="sec">Цена</div>
                    @if (count($item['sizes']))
                        <div class="size-row muted" style="font-size:12px;font-weight:600">
                            <span>Размер (напр. 27)</span><span>Цена, ֏</span><span>Вес / объём</span><span></span>
                        </div>
                        @foreach ($item['sizes'] as $i => $s)
                            <div class="size-row" wire:key="size-{{ $i }}">
                                <input class="inp" wire:model="item.sizes.{{ $i }}.label" placeholder="27">
                                <input class="inp" type="number" min="0" wire:model="item.sizes.{{ $i }}.price" placeholder="2750">
                                <input class="inp" wire:model="item.sizes.{{ $i }}.weight" placeholder="500 г">
                                <button type="button" class="btn sm icon" wire:click="removeSize({{ $i }})">×</button>
                            </div>
                            @error("item.sizes.$i.label")<div class="err">{{ $message }}</div>@enderror
                            @error("item.sizes.$i.price")<div class="err">{{ $message }}</div>@enderror
                        @endforeach
                        <button type="button" class="btn sm" wire:click="addSize">+ Размер</button>
                    @else
                        <div class="cols2">
                            <label class="f"><span>Цена, ֏ *</span><input class="inp" type="number" min="0" wire:model="item.price">
                                @error('item.price')<div class="err">{{ $message }}</div>@enderror</label>
                            <label class="f"><span>Вес / объём</span><input class="inp" wire:model="item.weight" placeholder="250 г"></label>
                        </div>
                        <button type="button" class="btn sm" wire:click="addSize">+ Несколько размеров (как у пиццы)</button>
                    @endif

                    <div class="sec">Прочее</div>
                    <label class="f"><span>Категория</span>
                        <select class="inp" wire:model="item.category_id">
                            @foreach ($this->categories as $c)
                                <option value="{{ $c->id }}">{{ $c->name_ru }}</option>
                            @endforeach
                        </select></label>
                    <label class="check"><input type="checkbox" wire:model="item.is_hit"> Метка «ХИТ»</label>
                    <label class="check"><input type="checkbox" wire:model="item.is_active"> Показывать в меню</label>
                </div>
                <div class="mf">
                    @if ($item['id'])
                        <button type="button" class="btn danger" style="margin-right:auto"
                                wire:click="deleteItem({{ $item['id'] }})"
                                wire:confirm="Удалить блюдо?">Удалить</button>
                    @endif
                    <button type="button" class="btn" wire:click="$set('showItem', false)">Отмена</button>
                    <button class="btn primary" wire:loading.attr="disabled">Сохранить</button>
                </div>
            </form>
        </div>
    @endif
</div>
