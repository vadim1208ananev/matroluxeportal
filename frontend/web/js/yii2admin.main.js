$(document).ready(function () {
    "use strict";

    $(document).ready(function () {
        // Check for click events on the navbar burger icon
        $(".navbar-burger").click(function () {
            // Toggle the "is-active" class on both the "navbar-burger" and the "navbar-menu"
            $(".navbar-burger").toggleClass("is-active");
            $(".navbar-menu").toggleClass("is-active");
        });
    });

    $('.ok-main-btn-search').on('click', function (e) {
        e.preventDefault();
        document.location.href = '/search';
    });

    $(".hero-foot ul li").hover(function () {
        $(".ok-main-tile").addClass("is-active");
    }, function () {
        $(".ok-main-tile").removeClass("is-active");
    });

    /******************************
     BOTTOM SCROLL TOP BUTTON
     ******************************/

    var scrollTop = $(".scroll-top");
    $(window).scroll(function () {
        var topPos = $(this).scrollTop();
        // if user scrolls down - show scroll to top button
        if (topPos > 100) {
            $(scrollTop).css("opacity", "1");
        } else {
            $(scrollTop).css("opacity", "0");
        }
    });

    $(scrollTop).click(function () {
        $('html, body').animate({
            scrollTop: 0
        }, 800);
        return false;
    });

    $(document).on('click', 'button.delete', function (e) {
        $(this).closest('.notification').hide();
    });

    // $('.nav-li').mouseenter(function () {
    //     $(this)
    //         .find('div.dropdown-menu')
    //         .not('.min-width768')
    //         .fadeIn(300)
    //         .css('display', 'block');
    // })
    //     .mouseleave(function () {
    //         $(this)
    //             .closest('ul')
    //             .find('div.dropdown-menu')
    //             .not('.min-width768')
    //             .css('display', 'none');
    //     });

    // $('.navigation>.nav-li').hover(
    //     function () {
    //         $(this).find('>a').css('background-color', '#8e83bc');
    //     },
    //     function () {
    //         $(this).find('>a').css('background-color', '#252525');
    //     });

    // $('.item-grid>ul>li').hover(
    //     function () {
    //         var self = $(this);
    //         self.find('ul').css('display', 'inline-flex');
    //         self.find('.text-center a').css('color', '#8e83bc');
    //         self.css('border', '1px solid #505050');
    //     },
    //     function () {
    //         var self = $(this);
    //         self.find('ul').css('display', 'none');
    //         self.find('.text-center a').css('color', '#505050');
    //         self.css('border', 'none');
    //     }
    // );

    $("select[id^='size-option']").on('change', function () {
        $('.ok-product-price').text($('option:selected', this).attr('data-price'));
    });

    $("select[class^='size-option']").on('change', function () {
        var self = $(this);
        var id = self.data('id');
        var attribute_idOld = self.closest('.ok-row').data('attribute-id');
        var attribute_id = $('option:selected', self).data('attribute-id');
        if (attribute_id === undefined) {
            attribute_id = '';
        }
        $.ajax({
            url: '/cart/changeattribute',
            data: {id: id, attribute_id: attribute_id, attribute_idOld: attribute_idOld},
            type: 'GET',
            success: function (e) {
                if (e) {
                    self.closest('.ok-row').data('attribute-id', attribute_id);
                    $('.price-item' + id).text($('option:selected', self).data('price'));
                    refreshQtySum();
                }
            },
            beforeSend: function () {
                self.closest('.select').addClass('is-loading');
            },
            complete: function () {
                self.closest('.select').removeClass('is-loading');
            },
        });
    });

    $('.ok-carousel-wishlist, .ok-product-wishlist').on('click', function (e) {
        var self = $(this);
        if (self.hasClass('is-warning')) {
            return;
        }
        e.preventDefault();
        self.addClass('is-warning');
        self.attr('href', '/wishlist');
        var doc = $(document);
        var id = self.data('id');
        $.ajax({
            url: '/wishlist/add',
            data: {id: id},
            type: 'GET',
            success: function (e) {
                if (!e)
                    alert('Error!');
                var wishlist = doc.find('.ok-main-badge-wishlist');
                var badge = wishlist.find('.badge');
                if (badge.length === 0) {
                    wishlist.html("<span class='badge' data-badge='" + e + "'>Избранное</span>");
                } else {
                    badge.attr('data-badge', e);
                }
                // window.location.replace("/wishlist");
            },
            beforeSend: function () {
                self.addClass('is-loading');
            },
            complete: function () {
                self.removeClass('is-loading');
            },
        });
    });

    $('.ok-cart-to-wishlist').on('click', function (e) {
        e.preventDefault();
        var doc = $(document);
        var self = $(this);
        var id = self.data('id');
        $.ajax({
            url: '/cart/wishlist',
            data: {id: id},
            type: 'GET',
            success: function (e) {
                removeItemCart(doc, self, true, true);
            },
            beforeSend: function () {
                self.addClass('is-loading');
            },
            complete: function () {
                self.removeClass('is-loading');
            },
        });
    });

    $('.ok-wishlist-remove').on('click', function (e) {
        e.preventDefault();
        var doc = $(document);
        var self = $(this);
        var id = self.data('id');
        $.ajax({
            url: '/wishlist/clear',
            data: {id: id},
            type: 'GET',
            success: function () {
                removeItemWishlist(doc, self, false);
            },
            beforeSend: function () {
                self.addClass('is-loading');
            },
            complete: function () {
                self.removeClass('is-loading');
            },
        });
    });

    $('.ok-cart-remove').on('click', function (e) {
        e.preventDefault();
        var doc = $(document);
        var self = $(this);
        var id = self.data('id');
        $.ajax({
            url: '/cart/clear',
            data: {id: id},
            type: 'GET',
            success: function () {
                removeItemCart(doc, self, false, true);
            },
            // beforeSend: function () {
            //     $('#loader').show();
            // },
            // complete: function () {
            //     $('#loader').hide();
            // },
        });
    });

    $('.ok-cart-checkout').on('click', function (e) {
        e.preventDefault();
        var self = $(this);
        $.ajax({
            url: '/cart/checkout',
            type: 'GET',
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj.error) {
                    alert(obj.error.msg);
                    return;
                }
                $(document).find('h1').after("<div class='notification is-success'><button class='delete'></button>Заказ успешно оформлен! Ждите звонка от представителя магазина!</div>");
                removeItemCartAll();
            },
            beforeSend: function () {
                self.addClass('is-loading');
            },
            complete: function () {
                self.removeClass('is-loading');
            },
        });
    });

    $('.ok-wishlist-to-cart').on('click', function (e) {
        e.preventDefault();
        var doc = $(document);
        var self = $(this);
        var id = self.data('id');
        $.ajax({
            url: '/wishlist/cart',
            data: {id: id},
            type: 'GET',
            success: function () {
                removeItemWishlist(doc, self, true);
            },
            beforeSend: function () {
                self.addClass('is-loading');
            },
            complete: function () {
                self.removeClass('is-loading');
            },
        });
    });

    $('.ok-product-cart,.ok-carousel-cart').on('click', function (e) {
        var self = $(this);
        if (self.hasClass('is-warning')) {
            self.attr('href', '/cart');
            return;
        }
        e.preventDefault();
        self.addClass('is-warning');
        // self.attr('href', '/cart');
        var doc = $(document);
        var id = self.data('id');
        var attribute_id = $('option:selected', $(document).find('#size-option')).val();
        if (attribute_id === undefined) {
            attribute_id = '';
        }
        $.ajax({
            url: '/cart/add',
            data: {id: id, attribute_id: attribute_id, pathname: window.location.pathname},
            type: 'GET',
            success: function () {
                var cart = doc.find('.ok-main-badge-cart');
                var badge = cart.find('.badge');
                if (badge.length === 0) {
                    cart.html("<span class='badge' data-badge='1'>Корзина</span>");
                } else {
                    badge.attr('data-badge', parseInt(badge.attr('data-badge')) + 1);
                }
            },
            beforeSend: function () {
                self.addClass('is-loading');
            },
            complete: function () {
                self.removeClass('is-loading');
            },
        });
    });

    $('.ok-wishlist-all-to-cart').on('click', function (e) {
        e.preventDefault();
        var self = $(this);
        $.ajax({
            url: '/wishlist/cartall',
            type: 'GET',
            success: function () {
                window.location.replace("/cart");
            },
            beforeSend: function () {
                self.addClass('is-loading');
            },
            complete: function () {
                self.removeClass('is-loading');
            },
        });
    });

    $('.ok-cart-minus,.ok-cart-plus').on('click', function (e) {
        e.preventDefault();
        var doc = $(document);
        var self = $(this);
        var sign = 0;

        var firstOdd = self.closest('.ok-row');
        var id = firstOdd.data('id');
        var attribute_id = firstOdd.data('attribute-id');
        if (self.hasClass('ok-cart-minus')) {
            sign = -1;
        } else if (self.has('ok-cart-plus')) {
            sign = 1;
        } else {
            return;
        }

        var input = self.closest('.field.has-addons').find('input');
        var qtyInput = parseInt(input.val());
        if (qtyInput + sign <= 0) {
            return;
        }

        $.ajax({
            url: '/cart/changeqty',
            data: {id: id, attribute_id: attribute_id, sign: sign},
            type: 'GET',
            success: function (e) {
                if (e) {
                    input.attr('value', qtyInput + sign);

                    var cart = doc.find('.ok-main-badge-cart');
                    var badge = cart.find('.badge');
                    var cartQty = parseInt(badge.attr('data-badge'));
                    cartQty += sign;
                    if (cartQty === 0) {
                        cart.text('Корзина');
                    } else {
                        badge.attr('data-badge', cartQty);
                    }

                    refreshQtySum();
                }
            },
            beforeSend: function () {
                self.addClass('is-loading');
            },
            complete: function () {
                self.removeClass('is-loading');
            },
        });
    });

    function removeItemWishlist(doc, self, moveToCart) {
        self.closest('tr').remove();

        var wishlist = doc.find('.ok-main-badge-wishlist');
        var badge = wishlist.find('.badge');
        var wishlistQty = parseInt(badge.attr('data-badge'));
        wishlistQty -= 1;
        if (wishlistQty === 0) {
            wishlist.text('Избранное');
        } else {
            badge.attr('data-badge', wishlistQty);
        }

        if (moveToCart) {
            var cart = doc.find('.ok-main-badge-cart');
            badge = cart.find('.badge');
            if (badge.length === 0) {
                cart.html("<span class='badge' data-badge='1'>Корзина</span>");
            } else {
                badge.attr('data-badge', parseInt(badge.attr('data-badge')) + 1);
            }
        }

        if ($('.ok-row').length === 0) {
            $('table').remove();
        }
    }

    function removeItemCart(doc, self, refreshBadgeWishlist, refreshBadgeCart) {
        var row = self.closest('.ok-row');

        if (refreshBadgeWishlist) {

            var wishlist = doc.find('.ok-main-badge-wishlist');
            var badge = wishlist.find('.badge');
            if (badge.length === 0) {
                wishlist.html("<span class='badge' data-badge='1'>Избранное</span>");
            } else {
                badge.attr('data-badge', parseInt(badge.attr('data-badge')) + 1);
            }
        }

        if (refreshBadgeCart) {
            var cart = doc.find('.ok-main-badge-cart');
            var badge = cart.find('.badge');
            var cartQty = parseInt(badge.attr('data-badge'));
            cartQty -= parseInt(row.find('input').val());
            if (cartQty === 0) {
                cart.text('Корзина');
            } else {
                badge.attr('data-badge', cartQty);
            }
        }

        row.remove();

        if ($('.ok-row').length === 0) {
            $('table').remove();
        }
        refreshQtySum();
    }

    function removeItemCartAll() {
        $('.ok-main-badge-cart').text('');
        // $('.checkout-types, #cart-view-form').remove();
        $('table').remove();
    }

    function refreshQtySum() {
        var _price = 0, _qty = 0, _sum = 0, qty = 0, sum = 0;
        $('.ok-row').each(function () {
            var self = $(this);
            var id = self.data('id');
            _price = parseInt(self.find('.price-item' + id).text().replace(' ', ''));
            _qty = parseInt(self.find('.ok-cart-input-qty').val());
            _sum = _price * _qty;
            self.find('.ok-cart-item-sum').text(addSeparators(_sum, '.', '.', ' '));
            qty += _qty;
            sum += _sum;
        });
        $('.ok-cart-qty').text(qty);
        $('.ok-cart-sum').text(addSeparators(sum, '.', '.', ' '));
    }

    function addSeparators(nStr, inD, outD, sep) {
        nStr += '';
        var dpos = nStr.indexOf(inD);
        var nStrEnd = '';
        if (dpos !== -1) {
            nStrEnd = outD + nStr.substring(dpos + 1, nStr.length);
            nStr = nStr.substring(0, dpos);
        }
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(nStr)) {
            nStr = nStr.replace(rgx, '$1' + sep + '$2');
        }
        return nStr + nStrEnd;
    }

    $(".ok-main-btn-call-us").click(function () {
        self = (this);
        $(".modal").addClass("is-active");
        $('.ok-main-modal-text').val($(self).data('name'));
    });

    $(".ok-main-modal-delete").click(function () {
        $(".modal").removeClass("is-active");
    });

    $(document).click(function (e) {
        if (!$(e.target).closest(".modal-card,.ok-main-btn-call-us").length) {
            $("body").find(".modal").removeClass("is-active");
        }
    });

    $('.ok-main-btn-modal-send').on('click', function (e) {
        e.preventDefault();
        var doc = $(document);
        var text = doc.find('.ok-main-modal-text').val();
        var phone = doc.find('.ok-main-modal-phone').val();
        console.log(text);
        console.log(phone);
        $.ajax({
            url: '/site/callus/',
            data: {text: text, phone: phone},
            type: 'GET',
            success: function (e) {
                console.log(e);
                doc.find('.ok-main-modal-call-us').removeClass('is-active');
            },
        });
    });

    //yii2admin
    $('.btn-remove').on('click', function (e) {
        e.preventDefault();
        if (confirm('Удалить?')) {
            window.location.href = $(this).getAttribute('href');
        }
        return false;
    });


});

document.addEventListener('DOMContentLoaded', function () {
    "use strict";

    // Dropdowns
    var $dropdowns = getAll('.dropdown:not(.is-hoverable)');

    if ($dropdowns.length > 0) {
        $dropdowns.forEach(function ($el) {
            $el.addEventListener('click', function (event) {
                event.stopPropagation();
                $el.classList.toggle('is-active');
            });
        });

        document.addEventListener('click', function (event) {
            closeDropdowns();
        });
    }

    function closeDropdowns() {
        $dropdowns.forEach(function ($el) {
            $el.classList.remove('is-active');
        });
    }

    // Close dropdowns if ESC pressed
    document.addEventListener('keydown', function (event) {
        var e = event || window.event;
        if (e.keyCode === 27) {
            closeDropdowns();
        }
    });

    // Functions
    function getAll(selector) {
        return Array.prototype.slice.call(document.querySelectorAll(selector), 0);
    }


    $('.ok-main-btn-filter').click(function () {
        var self = $(this);
        $('.ok-main-filter').slideToggle('slow', function () {
            var icon = self.find('svg');
            var iconAttr = icon.attr('data-icon');
            if (iconAttr === "times") {
                icon.attr('data-icon', 'filter');
            } else {
                icon.attr('data-icon', 'times');
            }
        });
    });

});
