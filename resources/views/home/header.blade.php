<div class="top">
    <div class="main">
        <div class="lf">

        </div>
        <div class="rt">
            <a href="javascript:st();void 0;" id="st" rel="nofollow">繁體中文</a> | <a href="" target="_blank">手机版</a> | <a href="">积分规则</a> | <a href="" rel="nofollow">放到桌面</a> | <a href="javascript:void(0);" onclick="AddFavorite('网',location.href)" target="_self" rel="nofollow">收藏本站</a>
        </div>
    </div>
</div>
<div class="wrapper">
    <div class="logo">
        <a href="/">LOGO</a>
    </div>
    <div class="seach">

    </div>
</div>
<div class="nav">
    <div class="main">
        <ul class="nav_l">
            <li><a href="/">首页</a></li>
            @foreach($categorys as $v)
                <li><a href="{!! url('books',['id' => $v['id']]) !!}">{{ $v['name'] }}</a></li>
            @endforeach
        </ul>
        <ul class="nav_r">
            <li><a href="">收藏</a></li>
            <li><a href="">新书</a></li>
            <li><a href="">完本</a></li>
            <li><a href="" rel="nofollow">求书</a></li>
        </ul>
    </div>
</div>