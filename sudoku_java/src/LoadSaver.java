/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package sudoku;

import java.io.File;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.ObjectInputStream;
import java.io.ObjectOutputStream;

/**
 *
 * @author Kuba
 */
public class LoadSaver {

    public static SaveGrid load(String file)
            throws ClassNotFoundException, IOException {

        FileInputStream fs = new FileInputStream(file);
        ObjectInputStream is = new ObjectInputStream(fs);
        SaveGrid res = (SaveGrid) is.readObject();
        is.close();
        is = null;
        fs = null;
        return res;
    }

    public static void save(SaveGrid grid, File file)
            throws IOException {

        FileOutputStream fs = new FileOutputStream(file);
        ObjectOutputStream os = new ObjectOutputStream(fs);
        os.writeObject(grid);
        os.close();
        os = null;
        fs = null;
    }
}
