
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Map;
import java.util.StringTokenizer;

public class Fs {

    static Map<String, String> frommorse = new HashMap<>();
    static Map<String, String> tomorse = new HashMap<>();
    
    static StringTokenizer st = new StringTokenizer("");
    static BufferedReader input = new BufferedReader(new InputStreamReader(System.in));
    //static BufferedReader input;

    static String nextToken() throws Exception {
        while (!st.hasMoreTokens()) {
            st = new StringTokenizer(input.readLine());
        }
        return st.nextToken();
    }

    static int nextInt() throws Exception {
        return Integer.parseInt(nextToken());
    }

    public static void fillTable() {
        frommorse.put(".-", "A");
        frommorse.put("-...", "B");
        frommorse.put("-.-.", "C");
        frommorse.put("-..", "D");
        frommorse.put(".", "E");
        frommorse.put("..-.", "F");
        frommorse.put("--.", "G");
        frommorse.put("....", "H");
        frommorse.put("..", "I");
        frommorse.put(".---", "J");
        frommorse.put("-.-", "K");
        frommorse.put(".-..", "L");
        frommorse.put("--", "M");
        frommorse.put("-.", "N");
        frommorse.put("---", "O");
        frommorse.put(".--.", "P");
        frommorse.put("--.-", "Q");
        frommorse.put(".-.", "R");
        frommorse.put("...", "S");
        frommorse.put("-", "T");
        frommorse.put("..-", "U");
        frommorse.put("...-", "V");
        frommorse.put(".--", "W");
        frommorse.put("-..-", "X");
        frommorse.put("-.--", "Y");
        frommorse.put("--..", "Z");
        frommorse.put("..--", "_");
        frommorse.put(".-.-", ",");
        frommorse.put("---.", ".");
        frommorse.put("----", "?");
        
        Iterator it = frommorse.entrySet().iterator();
        while (it.hasNext()) {
            Map.Entry pairs = (Map.Entry)it.next();
            tomorse.put(pairs.getValue().toString(), pairs.getKey().toString());
        }
    }
    
    public static void main(String[] args) throws IOException, Exception {
        fillTable();
        //System.out.println(frommorse);
        //System.out.println(tomorse);

        String line;
        //line = "AKADTOF_IBOETATUK_IJN";
        while ((line = input.readLine()) != null) {
            st = new StringTokenizer(line, "");
            
            String code = nextToken();
            String morsecode = "";
            StringBuilder numbers = new StringBuilder();
            for (int i = 0; i < code.length(); i++) {
                //char ccode = code.charAt(i);
                String tm = tomorse.get(code.substring(i, i + 1));
                morsecode += tm;
                //System.out.println(tm);
                numbers.append("" + tm.length());
            }
            
            String revnumbers = numbers.reverse().toString();
            String encoded = "";
            int k = 0;
            for (int i = 0; i < revnumbers.length(); i++) {
                int step = Integer.parseInt( revnumbers.substring(i, i + 1) );
                encoded += frommorse.get( morsecode.substring(k, k + step) );
                k += step;
            }
            
            System.out.println(encoded);
        }
    
    }
    
}
