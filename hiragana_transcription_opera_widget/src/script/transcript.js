var sec = true;
var latin = new Array('kya', 'kyu', 'kyo', 'sha', 'shu', 'sho', 'cha', 'chu', 'cho', 'nya', 'nyu', 'nyo', 'hya', 'hyu', 'hyo',
                      'mya', 'myu', 'myo', 'rya', 'ryu', 'ryo', 'gya', 'gyu', 'gyo', 'ja', 'ju', 'jo',
                      'bya', 'byu', 'byo', 'pya', 'pyu', 'pyo', 'kk', 'ss', 'tt', 'hh', 'mm', 'rr', 'yy', 'ww', 'gg', 'zz', 'dd', 'bb', 'pp',
                      'ka', 'ki', 'ku', 'ke', 'ko', 'sa', 'shi', 'tsu', 'su', 'se', 'so', 'ta', 'chi', 'cu', 'te', 'to',
                      'na', 'ni', 'nu', 'ne', 'no', 'ha', 'hi', 'fu', 'he', 'ho', 'ma', 'mi', 'mu', 'me', 'mo', 'ra', 'ri', 'ru', 're', 'ro',
                      'ya', 'yu', 'yo', 'n', 'wa', 'wi', 'we', 'wo', 'ga', 'gi', 'gu', 'ge', 'go', 'za', 'ji', 'zu', 'ze', 'zo',
                      'da', 'de', 'do', 'ba', 'bi', 'bu', 'be', 'bo', 'pa', 'pi', 'pu', 'pe', 'po', 'a', 'i', 'u', 'e', 'o');

var hiragana = new Array('きゃ', 'きゅ', 'きょ', 'しゃ', 'しゅ', 'しょ', 'ちゃ', 'ちゅ', 'ちょ', 'にゃ', 'にゅ', 'にょ', 'ひゃ', 'ひゅ', 'ひょ',
                         'みゃ', 'みゅ', 'みょ', 'りゃ', 'りゅ', 'りょ', 'ぎゃ', 'ぎゅ', 'ぎょ', 'じゃ', 'じゅ', 'じょ',
                         'びゃ', 'びゅ', 'びょ', 'ぴゃ', 'ぴゅ', 'ぴょ', 'っk', 'っs', 'っt', 'っh', 'っm', 'っr', 'っy', 'っw', 'っg', 'っz',
                         'っd', 'っb', 'っp', 'か', 'き', 'く', 'け', 'こ', 'さ', 'し', 'つ', 'す', 'せ', 'そ', 'た', 'ち', 'つ', 'て', 'と',
                         'な', 'に', 'ぬ', 'ね', 'の', 'は', 'ひ', 'ふ', 'へ', 'ほ', 'ま', 'み', 'む', 'め', 'も', 'ら', 'り', 'る', 'れ', 'ろ',
                         'や', 'ゆ', 'よ', 'ん', 'わ', 'ゐ', 'ゑ', 'を', 'が', 'ぎ', 'ぐ', 'げ', 'ご', 'ざ', 'じ', 'ず', 'ぜ', 'ぞ',
                         'だ', 'で', 'ど', 'ば', 'び', 'ぶ', 'べ', 'ぼ', 'ぱ', 'ぴ', 'ぷ', 'ぺ', 'ぽ', 'あ', 'い', 'う', 'え', 'お');

var latinext = new Array('kya', 'kyu', 'kyo', 'sha', 'shu', 'sho', 'cha', 'chu', 'cho', 'nya', 'nyu', 'nyo', 'hya', 'hyu', 'hyo',
                         'mya', 'myu', 'myo', 'rya', 'ryu', 'ryo', 'gya', 'gyu', 'gyo', 'ja', 'ju', 'jo',
                         'bya', 'byu', 'byo', 'pya', 'pyu', 'pyo',
                         'tsi', 'tyu', 'fyu', 'vyu', 'kwa', 'kwi', 'kwe', 'kwo', 'gwa',
                         'pye', 'fya', 'fyo', 'fye', 'mye', 'rye',
                         'wyu', 'vya', 'vyo', 'vye', 'kye', 'gye', 'gwi', 'gwe', 'gwo', 'tsyu', 'nye', 'hye', 'bye',
                         'she', 'che', 'tsa', 'tse', 'tso', 'dyu', 'shi', 'tsu', 'chi',
                         
                         'kk', 'ss', 'tt', 'hh', 'mm', 'rr', 'yy', 'ww', 'gg', 'zz', 'dd', 'bb', 'pp',
                         'ka', 'ki', 'ku', 'ke', 'ko', 'sa', 'su', 'se', 'so', 'ta', 'cu', 'te', 'to',
                         'na', 'ni', 'nu', 'ne', 'no', 'ha', 'hi', 'fu', 'he', 'ho', 'ma', 'mi', 'mu', 'me', 'mo', 'ra', 'ri', 'ru', 're', 'ro',
                         'ya', 'yu', 'yo', 'n', 'wa', 'wi', 'we', 'wo', 'ga', 'gi', 'gu', 'ge', 'go', 'za', 'ji', 'zu', 'ze', 'zo',
                         'da', 'de', 'do', 'ba', 'bi', 'bu', 'be', 'bo', 'pa', 'pi', 'pu', 'pe', 'po',
                         'je', 'ti', 'di', 'fa', 'fi', 'fe', 'fo',
                         'wa', 'ye', 'wi', 'we', 'wo', 'va', 'vi', 'vu', 've', 'vo',
                         'tu', 'du', 'yi', 'wu', 'si', 'zi', 'hu', 'la', 'li', 'lu', 'le', 'lo',                      

                         'a', 'i', 'u', 'e', 'o');
                      
var katakana = new Array('キャ', 'キュ', 'キョ', 'シャ', 'シュ', 'ショ', 'チャ', 'チュ', 'チョ', 'ニャ', 'ニュ', 'ニョ', 'ヒャ', 'ヒュ', 'ヒョ',
                         'ミャ', 'ミュ', 'ミョ', 'リャ', 'リュ', 'リョ', 'ギャ', 'ギュ', 'ギョ', 'ジャ', 'ジュ', 'ジョ',
                         'ビャ', 'ビュ', 'ビョ', 'ピャ', 'ピュ', 'ピョ',
                         'ツィ', 'テュ', 'フュ', 'ヴュ', 'クァ', 'クィ', 'クェ', 'クォ', 'グァ',
                         'ピェ', 'フャ', 'フョ', 'フィェ', 'ミェ', 'リェ',
                         'ウュ', 'ヴャ', 'ヴョ', 'ヴィェ', 'キェ', 'ギェ', 'グィ', 'グェ', 'グォ', 'ツュ', 'ニェ', 'ヒェ', 'ビェ',
                         'シェ', 'チェ', 'ツァ', 'ツェ', 'ツォ', 'デュ', 'シ', 'ツ', 'チ',
                         
                         'ッK', 'ッS', 'ッT', 'ッH', 'ッM', 'ッR', 'ッY', 'ッW', 'ッG', 'ッZ', 'ッD', 'ッB', 'ッP',
                         'カ', 'キ', 'ク', 'ケ', 'コ', 'サ', 'ス', 'セ', 'ソ', 'タ', 'ツ', 'テ', 'ト', 
                         'ナ', 'ニ', 'ヌ', 'ネ', 'ノ', 'ハ', 'ヒ', 'フ', 'ヘ', 'ホ', 'マ', 'ミ', 'ム', 'メ', 'モ', 'ラ', 'リ', 'ル', 'レ', 'ロ',
                         'ヤ', 'ユ', 'ヨ', 'ン', 'ワ', 'ヰ', 'ヱ', 'ヲ', 'ガ', 'ギ', 'グ', 'ゲ', 'ゴ', 'ザ', 'ジ', 'ズ', 'ゼ', 'ゾ',
                         'ダ', 'デ', 'ド', 'バ', 'ビ', 'ブ', 'ベ', 'ボ', 'パ', 'ピ', 'プ', 'ペ', 'ポ',
                         'ジェ', 'ティ', 'ディ', 'ファ', 'フィ', 'フェ', 'フォ',
                         'ウァ', 'イェ', 'ウィ', 'ウェ', 'ウォ', 'ヴァ', 'ヴィ', 'ヴ', 'ヴェ', 'ヴォ',
                         'トゥ', 'ドゥ', 'イィ', 'ウゥ', 'スィ', 'ズィ', 'ホゥ', 'ラ゜', 'リ゜', 'ル゜', 'レ゜', 'ロ゜',

                         'ア', 'イ', 'ウ', 'エ', 'オ');

//tabulka nejednoznačných znaků
var hiraganasec1 = new Array('じゃ', 'じゅ', 'じょ', 'じ', 'ず');
var hiraganasec2 = new Array('ぢゃ', 'ぢゅ', 'ぢょ', 'ぢ', 'づ');

var katakanasec1 = new Array('ジャ', 'ジュ', 'ジョ', 'ジ', 'ズ');
var katakanasec2 = new Array('ヂャ', 'ヂュ', 'ヂョ', 'ヂ', 'ヅ');

function rewriteToHiragana() {
  var elem = document.getElementById('trans');
  if (elem.value.length < 1) return;

  var lastChar = elem.value[elem.value.length - 1];
  var pLastChar = (elem.value.length > 1)? elem.value[elem.value.length - 2] : '';
  
  if (event.keyCode == 39 || event.keyCode == 40)
    n = true;
  
  //konec samohláskou nebo souhláskou n, malé tsu pro dvě stejná písmena
  if (lastChar == 'a' || lastChar == 'i' || lastChar == 'u' || lastChar == 'e' || lastChar == 'o' ||
      lastChar == pLastChar || n || lastChar == '-' || lastChar == '.' ||
      lastChar == 'A' || lastChar == 'I' || lastChar == 'U' || lastChar == 'E' || lastChar == 'O' ||
      lastChar == ' ' || lastChar == '!' || lastChar == '?' || lastChar == ',') {

        for (i = 0; i < hiragana.length; i++)
          elem.value = elem.value.replace(latin[i], hiragana[i]);

        for (i = 0; i < katakana.length; i++)
          elem.value = elem.value.replace(latinext[i].toUpperCase(), katakana[i]);

        elem.value = elem.value.replace('-', 'ー');
        elem.value = elem.value.replace('.', '。');
  }
  n = false;
  
  //F2 zamění nejednoznačnosti
  if (event.keyCode == 113) {
    var orig = elem.value;
    if (sec)
      for (i = 0; i < hiraganasec1.length; i++) {
        elem.value = elem.value.replace(hiraganasec1[i], hiraganasec2[i]);
        elem.value = elem.value.replace(katakanasec1[i], katakanasec2[i]);
      }
    else
      for (i = 0; i < hiraganasec2.length; i++) {
        elem.value = elem.value.replace(hiraganasec2[i], hiraganasec1[i]);
        elem.value = elem.value.replace(katakanasec2[i], katakanasec1[i]);
      }
    //nic se nezměnilo, přepneme stav
    if (orig == elem.value)
      sec = !sec;
  }
  
}
