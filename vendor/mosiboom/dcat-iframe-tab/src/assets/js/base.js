$(function () {
    /*引用swiper插件*/
    const swiper = new Swiper('.swiper-container', {
        slidesPerView: 'auto',
        spaceBetween: 0,
        freeMode: true,
        watchSlidesProgress: true,
        watchSlidesVisibility: true,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        observer: true,                         //开启监视者模式
        observeParents: true,                   //开启监视父类
        observeSlideChildren: true,             //开启监视子类
        mousewheel: {
            sensitivity: 0.3,                   //鼠标滚轮的控制速率
        },
        grabCursor: true,                       //开启抓手模式
    });
    /*管理元素*/
    const elements = {
        iframe_tab_container: $('#iframe-tab-container'),
        iframe_tab: $('#iframe-tab'),
        iframe_tab_link: $('#iframe-tab .nav-link'),
        iframe_tabContent: $('#iframe-tabContent'),
        item_close: $('.iframe-tab-close-btn'),
        menu_link: $('.main-menu .nav-link:not(.navbar-header .nav-link)'),
        menu_content: $('.main-menu-content .sidebar'),
        drop_menu_link: $(".dropdown-menu .dropdown-item"),
        drop_menu: $(".dropdown-menu"),
    }
    /*定义模板*/
    const iframeTabTemplate = {
        tabItem(html, id, use_close = true) {
            /*标签栏*/
            let close_html = ''
            let first_tag = 'data-first=1'
            if (use_close) {
                close_html = '<span title="关闭标签页" class="iframe-tab-close-btn"><i class="fa fa-minus-circle"></i></span>'
                first_tag = 'data-first=0'
            }
            return `
            <li class="nav-item swiper-slide" role="presentation">
                    <a ${first_tag} class="nav-link active" id="iframe-home-${id}" data-toggle="pill" href="#iframe-${id}" role="tab" aria-controls="iframe-${id}" aria-selected="true">
                        ${html}
                        ${close_html}
                    </a>
            </li>
            `
        },
        tabContentItem(url, id) {
            /*标签对应内容*/
            return `
            <div class="tab-pane fade show active" id="iframe-${id}" role="tabpanel" aria-labelledby="iframe-home-${id}">
                <iframe
                        style="position: absolute;width: 100%;height: 100%;left: 0;top: 0;right: 0;bottom: 0;"
                        src="${url}" width="100%" height="100%" frameborder="no" border="0" marginwidth="0"
                        marginheight="0"
                        scrolling-x="no" scrolling-y="auto" allowtransparency="yes"></iframe>
            </div>
            `
        }
    }
    /*Tab逻辑处理*/
    const iframeTab = {
        TAB_STORAGE_KEY: $('#use_id').val() + '_6d9e562706a26cd2',
        CLICK_TAB: '',
        USE_CACHE: parseInt($('#iframe_tab_cache').val()),
        LAZY_LOAD: parseInt($('#iframe_tab_lazy_load').val()),
        storageGet() {
            let data = localStorage.getItem(this.TAB_STORAGE_KEY)
            return JSON.parse(data) === null ? {} : JSON.parse(data)
        },
        storageSet(id, value) {
            let list = this.storageGet()
            list[id] = value
            let data = JSON.stringify(list)
            localStorage.setItem(this.TAB_STORAGE_KEY, data)
            return list;
        },
        storageDelete(id) {
            /*删除一个*/
            let data = this.storageGet()
            if (data[id]) {
                delete (data[id])
                localStorage.setItem(this.TAB_STORAGE_KEY, JSON.stringify(data))
            }
            return data
        },
        storageDeleteAll() {
            /*删除所有*/
            localStorage.removeItem(this.TAB_STORAGE_KEY)
        },
        clearDefaultMenuEvent() {
            elements.menu_link.unbind('click')
            elements.drop_menu_link.unbind('click')
            $('.navbar-header').find('a').unbind('click')
            let items = elements.menu_content.find('li')
            items.find('a').click(function (e) {
                let href = $(this).attr('href');
                if (!href || href === '#') {
                    return;
                }
                e.preventDefault()
                items.find('.nav-link').removeClass('active');
                $(this).addClass('active')
            })
            elements.drop_menu.find('.dropdown-item').click(function (e) {
                let href = $(this).attr('href');
                if (!href || href === '#') {
                    return;
                }
                e.preventDefault()
            })
        },
        menuClick() {
            let items = elements.menu_content.find('li')
            /*左侧菜单监听*/
            items.find('a').click(iframeTab.menuClickCallback);
            /*顶部菜单监听*/
            elements.drop_menu.find('a').click(iframeTab.menuClickCallback)
            /*点击logo重定向*/
            $('.navbar-header').find('a').click(function () {
                location.href = $(this).attr('href')
            })
        },
        menuClickCallback: function () {
            let html = $(this).html(),
                href = $(this).attr('href'),
                id = iframeTab.generateID(href)
            if (!href || href === '#') {
                return
            }
            /*登出跳转*/
            if (href.indexOf("logout") !== -1) {
                location.href = href
                return
            }
            let tab_html = iframeTabTemplate.tabItem(html, id),                 //生成tab的html
                tab_content_html = iframeTabTemplate.tabContentItem(href, id),  //生成tab content的html
                choose_element = iframeTab.findIframeTabActiveElement()
            /*移除tab bar 选中样式*/
            iframeTab.removeTabBarStyle()
            /*更新选中缓存中的tab bar*/
            iframeTab.cacheUpdateTabBar(choose_element)
            /*判断tab是否已经存在，不存在添加，存在则更新*/
            if (elements.iframe_tab.find(`#iframe-home-${id}`).length <= 0) {
                swiper.appendSlide(tab_html)
                elements.iframe_tabContent.append(tab_content_html)
                let iframeTab_element = $(`#iframe-home-${id}`),             //获取tab的元素对象
                    _index = iframeTab_element.parents('.nav-item').index(), //获取下标
                    content_element = $(`#iframe-${id}`)                     //获取tab content的元素对象
                swiper.slideTo(_index)
                swiper.updateSlides()
                iframeTab_element.addClass('active')
                iframeTab_element.attr('aria-selected', 'true')
                content_element.addClass('active')
                content_element.addClass('show')
                iframeTab.cacheUpdateTabBar(iframeTab_element)
            } else {
                /*模拟点击*/
                elements.iframe_tab.find(`#iframe-home-${id}`).click()
            }
        },
        joinFirstMenu() {
            /*获取第一条菜单包括图标信息并添加到tab*/
            let first_menu_html = $(elements.menu_link[0]).html()
            let first_url = $(elements.menu_link[0]).attr('href');
            let first_id = this.generateID(first_url);
            swiper.appendSlide(iframeTabTemplate.tabItem(first_menu_html, first_id, false))
            elements.iframe_tabContent.append(iframeTabTemplate.tabContentItem(first_url, first_id))
            swiper.updateSlides();
        },
        removeTabBarStyle() {
            /*移除tab bar 选中样式*/
            elements.iframe_tab.find('.nav-link').removeClass('active');
            elements.iframe_tab.find('.nav-link').attr('aria-selected', 'false')
            elements.iframe_tabContent.find('.tab-pane').removeClass('active', 'show')
        },
        closeAdjacentOperate(adjacent) {
            /*关闭标签后相邻兄弟元素的选择*/
            adjacent.find(`.nav-link`).click()
            iframeTab.removeTabBarStyle()
            adjacent.find(`.nav-link`).addClass('active');
            adjacent.find(`.nav-link`).attr('aria-selected', 'true')
            let content_href = adjacent.find('.nav-link').attr('href')
            elements.iframe_tabContent.find(content_href).addClass('active')
            elements.iframe_tabContent.find(content_href).addClass('show')
        },
        iframeTabEventRegister() {
            /*按关闭按钮关闭*/
            $(document).on('click', '.iframe-tab-close-btn', function (e) {
                let can_delete = $(this).parents(".nav-link").attr('data-first');
                if (can_delete === '1') {
                    return;
                }
                let parent_obj = $(this).parents(".nav-item")
                /*如果是关闭当前选中的标签页，则下一个有选下一个，否则选上一个*/
                if ($(this).parents(".nav-link").hasClass('active')) {
                    let next_obj = parent_obj.next()
                    let prev_obj = parent_obj.prev()
                    if (next_obj.length > 0) {
                        iframeTab.closeAdjacentOperate(next_obj)
                    } else {
                        iframeTab.closeAdjacentOperate(prev_obj)
                    }
                }
                let tab_content_element = $($(this).parents(".nav-link").attr('href'))
                parent_obj.remove()
                tab_content_element.remove()
                if (iframeTab.USE_CACHE === 1) {
                    iframeTab.storageDelete($(this).parents(".nav-link").attr('id').split("-").pop())
                }
                e.stopPropagation()
            });
            /*双击关闭*/
            $(document).on('dblclick', '#iframe-tab .nav-link', function (e) {
                $(this).find('.iframe-tab-close-btn').click()
                return false
            });
            /*联动菜单样式*/
            $(document).on('click', '#iframe-tab .nav-link', function () {
                let content_id = $(this).attr('href')
                if (iframeTab.LAZY_LOAD === 1 && $(`${content_id}`).length <= 0) {
                    let content_without_suffix = content_id.replace('#iframe-', "")
                    console.log(content_without_suffix);
                    console.log(iframeTab.storageGet());
                    elements.iframe_tabContent.append(iframeTab.storageGet()[content_without_suffix].tab_content_html)
                    iframeTab.removeTabBarStyle()
                }
                let content_element = $(`${content_id}`)
                iframeTab.linkMenuAndIframeTab(content_id)
                $(this).addClass('active');
                $(this).attr('aria-selected', 'true')
                content_element.addClass('active')
                content_element.addClass('show')
                let _index = $(this).parents('.nav-item').index()
                swiper.slideTo(_index)
                swiper.updateSlides();
                iframeTab.cacheUpdateTabBar($(this))

            });
            /*获取上一个活动标签*/
            $(document).on('hidden.bs.tab', '#iframe-tab .nav-link', function (event) {
                iframeTab.cacheUpdateTabBar($(event.target))
            });

            /*右键菜单*/
            $(document).on('mousedown', '#iframe-tab .nav-link', function (event) {
                document.oncontextmenu = function () {
                    return false;
                }
                // let event = window.event || arguments.callee.caller.arguments[0]
                let key = event.which;//获取鼠标键位
                if (key === 3) {//1：代表左键；2：代表中键；3：代表右键
                    //获取右键点击坐标
                    let x = event.clientX;
                    let y = event.clientY;
                    $('.mouse-click-menu').show().css({left: x, top: y});
                    iframeTab.CLICK_TAB = $(this)
                }
            });
        },
        rightClickEventRegister() {
            /*复制标签页链接*/
            $(document).on('click', '.tab-copy-link', function () {
                if (iframeTab.CLICK_TAB !== '') {
                    let content_id = iframeTab.CLICK_TAB.attr("href")
                    let content = $(`${content_id} > iframe`).attr("src")
                    let $temp = $('<input>');
                    $("body").append($temp);
                    $temp.val(content).select();
                    document.execCommand("copy");
                    $temp.remove();
                    $(this).tooltip('show');
                    Dcat.success('复制成功');
                }
                document.oncontextmenu = function () {
                    return true;
                }
            })
            /*在新标签页中打开*/
            $(document).on('click', '.tab-open-link', function () {
                if (iframeTab.CLICK_TAB !== '') {
                    let content_id = iframeTab.CLICK_TAB.attr("href")
                    let content = $(`${content_id} > iframe`).attr("src")
                    window.open(content)
                }
                document.oncontextmenu = function () {
                    return true;
                }
            })
            /*关闭所有标签页*/
            $(document).on('click', '.tab-close-all', function () {
                if (iframeTab.CLICK_TAB !== '') {
                    elements.iframe_tab.find('.nav-link').each(function () {
                        let can_delete = $(this).attr('data-first');
                        if (can_delete === '1') {
                            return;
                        }
                        $(this).find('.iframe-tab-close-btn').click()
                    })
                }
                document.oncontextmenu = function () {
                    return true;
                }
            })
            /*关闭其他标签页*/
            $(document).on('click', '.tab-close-other', function () {
                if (iframeTab.CLICK_TAB !== '') {
                    elements.iframe_tab.find('.nav-link').each(function () {
                        let can_delete = $(this).attr('data-first');
                        if (can_delete === '1') {
                            return;
                        }
                        if (iframeTab.CLICK_TAB.attr('id') === $(this).attr('id')) {
                            iframeTab.CLICK_TAB.click()
                            return;
                        }
                        iframeTab.cacheUpdateTabBar($(this))
                        $(this).find('.iframe-tab-close-btn').click()
                    })
                }
                document.oncontextmenu = function () {
                    return true;
                }
            })
            /*清空缓存*/
            $(document).on('click', '.tab-clear-cache', function () {
                iframeTab.storageDeleteAll()
                Dcat.success('缓存已清空');
                elements.iframe_tab.html('')
                elements.iframe_tabContent.html('')
                iframeTab.joinFirstMenu()
                elements.menu_content.find('.nav-link.active').removeClass('active')
                $(elements.menu_link[0]).addClass('active')
                document.oncontextmenu = function () {
                    return true;
                }
            })
            /*刷新当前标签页*/
            $(document).on('click', '.tab-refresh', function () {
                if (iframeTab.CLICK_TAB !== '') {
                    let iframe_element = $(`${iframeTab.CLICK_TAB.attr("href")} > iframe`),
                        src = iframe_element.attr('src')
                    iframe_element.attr('src', '')
                    iframe_element.attr('src', src)
                    Dcat.success('页面已刷新')
                }
                document.oncontextmenu = function () {
                    return true;
                }
            })
            /*全局点击事件，释放浏览器默认右键菜单*/
            $(document).on('click', function () {
                document.oncontextmenu = function () {
                    return true;
                }
                $('.mouse-click-menu').hide();
            })
        },
        cacheInit() {
            if (this.USE_CACHE === 0) {
                this.storageDeleteAll()
                return;
            }
            let list = this.storageGet()
            console.log(list);
            if (list.length === 0 || JSON.stringify(list) === "{}") {
                return;
            }
            iframeTab.removeTabBarStyle()
            for (let i in list) {
                swiper.appendSlide(list[i].tab_html)
            }
            if (iframeTab.LAZY_LOAD === 0) {
                for (let i in list) {
                    elements.iframe_tabContent.append(list[i].tab_content_html)
                }
            }
            /*如果html里面没有active,则默认使用第一个*/
            let active_ele = iframeTab.findIframeTabActiveElement()
            let is_first = false;
            if (active_ele.length <= 0) {
                is_first = true;
                let first_url = $(elements.menu_link[0]).attr('href');
                let first_id = this.generateID(first_url);
                $(`#iframe-home-${first_id}`).click()
            }
            let content_id = active_ele.attr('href')
            if (iframeTab.LAZY_LOAD === 1 && !is_first) {
                let content_without_suffix = content_id.replace('#iframe-', "")
                console.log(content_without_suffix);
                console.log(list[content_without_suffix].tab_content_html);
                elements.iframe_tabContent.append(list[content_without_suffix].tab_content_html)
            }
            iframeTab.linkMenuAndIframeTab(content_id)
        },
        cacheUpdateTabBar(tab_link_element) {
            if (this.USE_CACHE !== 1) {
                return;
            }
            /*更新TabBar的html*/
            if (tab_link_element.attr('data-first') !== '1') {
                let id = tab_link_element.attr('id').split("-").pop();
                let tab_html = tab_link_element.parents('li').prop('outerHTML')
                let tab_content_html = $(`#iframe-${id}`).prop('outerHTML')
                this.storageSet(id, {id, tab_html, tab_content_html})
            }
        },
        findIframeTabActiveElement() {
            /*寻找tab里面选中的元素并返回*/
            return elements.iframe_tab.find('.nav-link.active')
        },
        linkMenuAndIframeTab(content_id) {
            /*链接Iframe tab和Menu*/
            let href = $(`${content_id} > iframe`).attr('src')
            let items = elements.menu_content.find('li')
            items.find('a').each(function () {
                let item_href = $(this).attr('href')
                if (!item_href || item_href === '#') {
                    return;
                }
                if (item_href === href) {
                    items.find('.nav-link').removeClass('active');
                    $(this).addClass('active')
                    let parent_obj = $(this).parents('.has-treeview')
                    if (parent_obj.length > 0 && !parent_obj.hasClass('menu-open')) {
                        parent_obj.find("a[href='#']").click()
                    }
                }
            })
        },
        init() {
            /*清除pjax默认菜单a标签点击事件*/
            this.clearDefaultMenuEvent()
            /*加入第一条默认菜单*/
            this.joinFirstMenu()
            /*菜单监听*/
            this.menuClick()
            /*缓存标签页处理*/
            this.cacheInit()
            /*事件注册*/
            this.iframeTabEventRegister()
            /*右键事件注册*/
            this.rightClickEventRegister()
            /*兼容dcat夜间模式*/
            this.darkMode()
        },
        darkMode() {
            const storage = window.parent.localStorage || {
                    setItem: function () {
                    }, getItem: function () {
                    }
                },
                key = 'dcat-admin-theme-mode',
                mode = storage.getItem(key)

            if (mode === 'dark') {
                elements.iframe_tab_container.addClass('sidebar-dark-white')
            }
            $(document).on('dark-mode.shown', function () {
                elements.iframe_tab_container.addClass('sidebar-dark-white')
            });

            $(document).on('dark-mode.hide', function () {
                elements.iframe_tab_container.removeClass('sidebar-dark-white')
            });
        },
        /*生成ID*/
        generateID(href) {
            return md5(href + this.TAB_STORAGE_KEY).substr(8, 16)
        },
    }
    /*挂载*/
    window.iframeTabParent = {swiper, elements, iframeTabTemplate, iframeTab}
    iframeTab.init()
})
