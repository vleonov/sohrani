$(document).ready(
    function() {
        Lazyload.init();
    }
);

var Lazyload = {

    $list: null,
    $window: null,
    timer: null,
    isOn: true,

    init: function()
    {
        this.$list = $('.a-lazyload');
        this.isOn = this.$list.length > 0;

        if (!this.isOn) {
            return;
        }

        this.$window = $(window);
        this.$window.scroll(function() {
            if (Lazyload.isOn) {
                clearTimeout(Lazyload.timer);
                Lazyload.timer = setTimeout(
                    function() {Lazyload.scrollListen()},
                    100
                );
            }
        });
    },

    scrollListen: function()
    {
        var dHeight = $(document).innerHeight(),
            wHeight = this.$window.innerHeight(),
            scrollTop = window.pageYOffset;

        if (dHeight - wHeight < wHeight + scrollTop) {
            Lazyload.load();
        }
    },

    loadTo: function($ele)
    {
        console.log(11);
        if (!this.isOn) {
            return;
        }
        var i, ci,
            $parent = $ele.parents('.a-lazyload'),
            src,
            $item,
            $img;

        if (!$parent.length) {
            return;
        }

        src = $('img', $parent).attr('data-src');
        if (!src) {
            return;
        }

        for (i = 0, ci = this.$list.length; i<ci; i++) {
            $img = $('img', this.$list[i]);
            if (src == $img.attr('data-src')) {
                this.load(i);
                break;
            }
        }
    },

    load: function(cnt)
    {
        var cnt = cnt || 9,
            i,
            $item,
            $img;

        for (i = 0; i<=cnt; i++) {
            $item = $(this.$list.get(i));
            if (!$item) {
                break;
            }

            $item.show();
            $img = $('img', $item);
            $img.attr('src', $img.attr('data-src'))
        }

        this.isOn = this.$list.length > 0;
        this.$list.splice(0, i);
    }
}