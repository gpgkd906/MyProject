var baidu = baidu || {};
baidu.editor = baidu.editor || {};
//todo: i18n
baidu.editor.config = {
    UEDITOR_HOME_URL: '../', //这里你可以配置成ueditor目录在您网站的绝对路径
    defaultToolbars: [
        ['FullScreen','Source','|','Undo','Redo','|',
         'Bold','Italic','Underline','StrikeThrough','Superscript','Subscript','RemoveFormat','FormatMatch','|',
         'BlockQuote','|',
         'PastePlain','|',
         'ForeColor','BackColor','InsertOrderedList','InsertUnorderedList','|',
         'Paragraph','FontFamily','FontSize','|',
         'DirectionalityLtr','DirectionalityRtl','|',
         'JustifyLeft','JustifyCenter','JustifyRight','JustifyJustify','|',
         'Link','Unlink','Image','Spechars','Emoticon','Video','Map','GMap', '|',
         'Horizontal','Date','Time','|',
         'InsertTable','DeleteTable','InsertParagraphBeforeTable','InsertRow','DeleteRow','InsertCol','DeleteCol','MergeCells','MergeRight','MergeDown','SplittoCells','SplittoRows','SplittoCols','|',
         'SelectAll','ClearDoc','SearchReplace','Print','Preview','Help']
    ],
    defaultLabelMap: {
        'undo': '撤销',
        'redo': '重做',
        'bold': '加粗',
        'italic': '斜体',
        'underline': '下划线',
        'strikethrough': '删除线',
        'subscript': '下标',
        'superscript': '上标',
        'formatmatch': '格式刷',
        'source': '源代码',
        'blockquote': '引用',
        'pasteplain': '纯文本粘贴模式',
        'selectall': '全选',
        'print': '打印',
        'preview': '预览',
        'horizontal': '分隔线',
        'removeformat': '清除格式',
        'time': '时间',
        'date': '日期',
        'unlink': '祛除链接',
        'insertrow': '前插入行',
        'insertcol': '前插入列',
        'mergeright': '右合并单元格',
        'mergedown': '下合并单元格',
        'deleterow': '删除行',
        'deletecol': '删除列',
        'splittorows': '拆分成行',
        'splittocols': '拆分成列',
        'splittocells': '完全拆分单元格',
        'mergecells': '合并多个单元格',
        'deletetable': '删除表格',
//        'tablesuper': '表格高级设置',
        'insertparagraphbeforetable': '表格前插行',
        'cleardoc': '清空文档',
        'fontfamily': '字体',
        'fontsize': '字号',
        'paragraph': '格式',
        'image': '图片',
        'inserttable': '表格',
        'link': '超链接',
        'emoticon': '表情',
        'spechars': '特殊字符',
        'searchreplace': '查询替换',
        'map': 'Baidu地图',
        'gmap': 'Google地图',
        'video': '视频',
        'help': '帮助',
        'justifyleft':'居左对齐',
        'justifyright':'居右对齐',
        'justifycenter':'居中对齐',
        'justifyjustify':'两端对齐',
        'forecolor' : '字体颜色',
        'backcolor' : '背景色',
        'insertorderedlist' : '有序列表',
        'insertunorderedlist' : '无序列表',
        'fullscreen' : '全屏',
        'directionalityltr' : '从左向右输入',
        'directionalityrtl' : '从右向左输入'
    },
    defaultIframeUrlMap: {
        'image': '~/public_html/js/dialogs/image/image.html',
        'inserttable': '~/public_html/js/dialogs/table/table.html',
        'link': '~/public_html/js/dialogs/link/link.html',
        'emoticon': '~/public_html/js/dialogs/emoticon/emoticon.html',
        'spechars': '~/public_html/js/dialogs/spechars/spechars.html',
        'searchreplace': '~/public_html/js/dialogs/searchreplace/searchreplace.html',
        'map': '~/public_html/js/dialogs/map/map.html',
        'gmap': '~/public_html/js/dialogs/gmap/gmap.html',
        'video': '~/public_html/js/dialogs/video/video.html',
        'help': '~/public_html/js/dialogs/help/help.html'
    },
    defaultListMap: {
        'fontfamily': ['宋体', '楷体', '隶书', '黑体','andale mono','arial','arial black','comic sans ms','impact','times new roman'],
        'fontsize': [10, 11, 12, 14, 16, 18, 20, 24, 36],
        'underline':['none','overline','line-through','underline'],
        'paragraph': ['p:Paragraph', 'h1:Heading 1', 'h2:Heading 2', 'h3:Heading 3', 'h4:Heading 4', 'h5:Heading 5', 'h6:Heading 6']
    },
    FONT_MAP: {
        '宋体': ['宋体', 'SimSun'],
        '楷体': ['楷体', '楷体_GB2312', 'SimKai'],
        '黑体': ['黑体', 'SimHei'],
        '隶书': ['隶书', 'SimLi'],
        'andale mono' : ['andale mono'],
        'arial' : ['arial','helvetica','sans-serif'],
        'arial black' : ['arial black','avant garde'],
        'comic sans ms' : ['comic sans ms'],
        'impact' : ['impact','chicago'],
        'times new roman' : ['times new roman']
    }
};