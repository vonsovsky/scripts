/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package sudoku;

import java.awt.event.*;
import javax.swing.*;
import java.awt.*;
import java.io.File;

/**
 *
 * @author Kuba
 */
public class SwingApp extends JFrame implements ActionListener, MouseListener {

    JButton show = new JButton("Zobrazit");
    JButton generate = new JButton("Nové");
    JButton solve = new JButton("Vyřešit zadání");
    JButton check = new JButton("Zkontrolovat");
    JMenuBar menuBar;
    JMenu menu_file, menu_help;
    JMenuItem menuItem_new, menuItem_check, menuItem_show, menuItem_solve, menuItem_difficulty, 
            menuItem_close, menuItem_about, menuItem_save, menuItem_load;
    JRadioButtonMenuItem radio_difficulty1, radio_difficulty2, radio_difficulty3;
    Container cp;

    final JFileChooser fc = new JFileChooser();

    SudokuApp sapp = new SudokuApp();

    public SwingApp() {
        setTitle("Sudoku");
        setSize(435, 470);
        setLocation(100, 100);

        addWindowListener(new WindowAdapter() {

            @Override
            public void windowClosing(WindowEvent e) {
                System.exit(0);
            }
        });
        sapp.dp.add(generate);
        generate.setVisible(true);
        generate.addActionListener(this);
        sapp.dp.add(check);
        check.setVisible(true);
        check.addActionListener(this);
        sapp.dp.add(show);
        show.setVisible(true);
        show.addActionListener(this);
        sapp.dp.add(solve);
        solve.setVisible(true);
        solve.addActionListener(this);

        menuBar = new JMenuBar();
        menu_file = new JMenu("Soubor");
        menu_file.setMnemonic(KeyEvent.VK_S);
        //Nový
        menuItem_new = new JMenuItem("Nové", KeyEvent.VK_N);
        menuItem_new.addActionListener(this);
        menu_file.add(menuItem_new);
        //Zkontrolovat
        menuItem_check = new JMenuItem("Zkontrolovat", KeyEvent.VK_Z);
        menuItem_check.addActionListener(this);
        menu_file.add(menuItem_check);
        //Zobrazit
        menuItem_show = new JMenuItem("Zobrazit", KeyEvent.VK_A);
        menuItem_show.addActionListener(this);
        menu_file.add(menuItem_show);
        //Vyřešit zadání
        menuItem_solve = new JMenuItem("Vyřešit zadání", KeyEvent.VK_V);
        menuItem_solve.addActionListener(this);
        menu_file.add(menuItem_solve);
        menu_file.addSeparator();
        //Obtížnosti
        menuItem_difficulty = new JMenu("Obtížnost");
        menuItem_difficulty.setMnemonic(KeyEvent.VK_O);
        menuItem_difficulty.addActionListener(this);
        //Uložit
        menuItem_save = new JMenuItem("Uložit", KeyEvent.VK_U);
        menuItem_save.addActionListener(this);
        menu_file.add(menuItem_save);
        // Načíst
        menuItem_load = new JMenuItem("Načíst", KeyEvent.VK_T);
        menuItem_load.addActionListener(this);
        menu_file.add(menuItem_load);
        menu_file.addSeparator();

        ButtonGroup group = new ButtonGroup();

        radio_difficulty1 = new JRadioButtonMenuItem("Obtížnost 1");
        radio_difficulty1.setMnemonic(KeyEvent.VK_1);
        radio_difficulty1.addActionListener(this);
        if (sapp.dp.difficulty == 1) {
            radio_difficulty1.setSelected(true);
        }
        group.add(radio_difficulty1);
        menuItem_difficulty.add(radio_difficulty1);

        radio_difficulty2 = new JRadioButtonMenuItem("Obtížnost 2");
        radio_difficulty1.setMnemonic(KeyEvent.VK_2);
        radio_difficulty2.addActionListener(this);
        if (sapp.dp.difficulty == 2) {
            radio_difficulty2.setSelected(true);
        }
        group.add(radio_difficulty2);
        menuItem_difficulty.add(radio_difficulty2);

        radio_difficulty3 = new JRadioButtonMenuItem("Obtížnost 3");
        radio_difficulty1.setMnemonic(KeyEvent.VK_3);
        radio_difficulty3.addActionListener(this);
        if (sapp.dp.difficulty == 3) {
            radio_difficulty3.setSelected(true);
        }
        group.add(radio_difficulty3);
        menuItem_difficulty.add(radio_difficulty3);
        menu_file.add(menuItem_difficulty);
        menu_file.addSeparator();
        //Zavřít
        menuItem_close = new JMenuItem("Zavřít");
        menuItem_close.addActionListener(this);
        menu_file.add(menuItem_close);

        menuBar.add(menu_file);

        //Nápověda - O programu
        menu_help = new JMenu("Nápověda");
        menu_help.setMnemonic(KeyEvent.VK_N);
        menuItem_about = new JMenuItem("O programu", KeyEvent.VK_O);
        menuItem_about.addActionListener(this);
        menu_help.add(menuItem_about);
        menuBar.add(menu_help);

        setJMenuBar(menuBar);


        sapp.generujSudoku();
        cp = this.getContentPane();
        cp.add(sapp.dp);

        cp.addMouseListener(this);
        addMouseListener(this);
    }

    public void zkontrolujDesku() {
        boolean status = true;
        sapp.prednastavPole();
        status = sapp.zkontrolujMoznosti();
        if (!status || !sapp.kontrolaPole()) {
            JOptionPane.showMessageDialog(this, "Tabule obsahuje chybu", "Chyba", JOptionPane.WARNING_MESSAGE);
        } else {
            int vyplneno = 0;
            for (int i = 0; i < 81; i++) {
                if (sapp.dp.hraci_deska[i / 9][i % 9] > 0) {
                    vyplneno++;
                }
            }
            if (vyplneno < 81) {
                JOptionPane.showMessageDialog(this, "Tabule není kompletní", "Chyba", JOptionPane.WARNING_MESSAGE);
            } else {
                JOptionPane.showMessageDialog(this, "Sudoku vyřešeno, gratuluji", "Konec hry", JOptionPane.INFORMATION_MESSAGE);
            }
        }
    }

    public void actionPerformed(ActionEvent e) {
        if (e.getSource() == check || e.getSource() == menuItem_check) {
            zkontrolujDesku();
        }
        if (e.getSource() == show || e.getSource() == menuItem_show) {
            sapp.zobrazDesku();
            sapp.dp.repaint();
        }
        if (e.getSource() == solve || e.getSource() == menuItem_solve) {
            sapp.prednastavPole();
            boolean status = sapp.zkontrolujMoznosti();
            if (!status || !sapp.kontrolaPole()) {
                JOptionPane.showMessageDialog(this, "Tabule obsahuje chybu", "Chyba", JOptionPane.WARNING_MESSAGE);
            } else if (sapp.vyresDesku()) {
                sapp.dp.repaint();
            }
        }
        if (e.getSource() == generate || e.getSource() == menuItem_new) {
            sapp.generujSudoku();
            sapp.dp.repaint();
        }
        if (e.getSource() == menuItem_about) {
            JOptionPane.showMessageDialog(this, "Řešení sudoku člověkem nebo počítačem - Petr Bartusek",
                    "Aplikace Sudoku", JOptionPane.INFORMATION_MESSAGE);
        }
        if (e.getSource() == menuItem_save) {
            doSave();
        }
        if (e.getSource() == menuItem_load) {
            doLoad();
        }
        if (e.getSource() == radio_difficulty1) {
            sapp.dp.difficulty = 1;
        }
        if (e.getSource() == radio_difficulty2) {
            sapp.dp.difficulty = 2;
        }
        if (e.getSource() == radio_difficulty3) {
            sapp.dp.difficulty = 3;
        }
        if (e.getSource() == menuItem_close) {
            System.exit(0);
        }
    }

    public void mouseClicked(MouseEvent e) {
    }

    @SuppressWarnings("static-access")
    public void mousePressed(MouseEvent e) {
        if (e.getX() >= 375 && e.getX() < 415 && e.getY() >= 40 && e.getY() < 405) {
            sapp.dp.vybrane_cislo = (e.getY() - 45) / 40 + 1;
            sapp.dp.repaint();
        }
        if (e.getX() >= 5 && e.getX() < 365 && e.getY() >= 45 && e.getY() < 405) {
            if (e.getButton() == e.BUTTON1) {
                if (sapp.dp.hraci_deska[(e.getY() - 45) / 40][(e.getX() - 5) / 40] == 0) {
                    sapp.dp.hraci_deska[(e.getY() - 45) / 40][(e.getX() - 5) / 40] = sapp.dp.vybrane_cislo;
                    sapp.dp.repaint();
                }
                int vyplneno = 0;
                for (int i = 0; i < 81; i++) {
                    if (sapp.dp.hraci_deska[i / 9][i % 9] > 0) {
                        vyplneno++;
                    }
                }
                if (vyplneno == 81) {
                    zkontrolujDesku();
                }
            } else if (e.getButton() == e.BUTTON3) {
                sapp.dp.hraci_deska[(e.getY() - 45) / 40][(e.getX() - 5) / 40] = 0;
                sapp.dp.repaint();
            }
        }
    }

    public void mouseReleased(MouseEvent e) {
    }

    public void mouseEntered(MouseEvent e) {
    }

    public void mouseExited(MouseEvent e) {
    }

    private void doSave() {
        if (fc.showSaveDialog(this) == JFileChooser.APPROVE_OPTION) {
            try {
                SaveGrid sg = sapp.dp.getSerGrid();
                File file = fc.getSelectedFile();
                String aPath = file.getAbsolutePath();
                if(!aPath.toLowerCase().endsWith(".ssud")) {
                    aPath = aPath + ".ssud";
                    file = new File(aPath);
                }
                LoadSaver.save(sg, file);
                fc.setSelectedFile(file);
            } catch (Exception e) {
                e.printStackTrace();

                JOptionPane.showMessageDialog(this, "Chyba při ukládání", "",
                        JOptionPane.ERROR_MESSAGE);
            }
        }
    }

    private void doLoad() {
        if (fc.showOpenDialog(this) == JFileChooser.APPROVE_OPTION) {
            try {
                SaveGrid sg = LoadSaver.load(fc.getSelectedFile().getAbsolutePath());
                sapp.dp.setSerGrid(sg);
                sapp.dp.repaint();
            } catch (Exception e) {
                e.printStackTrace();
                JOptionPane.showMessageDialog(this, "Chyba při nahrávání", "Error",
                        JOptionPane.ERROR_MESSAGE);
            }
        }
    }
}

//FileWriter fw = new FileWriter(jmenoSouboru);
//        PrintWriter pw = new PrintWriter(fw);
//
//        pw.println("#dsgfdsfg");
//        Iterator<Usecka> i = su.iterate();
//        while(i.hasNext()) {
//            Usecka u = i.next();
//            pw.print(u.getName());
//            pw.println(" "+printBod(u.getBodA())+" "+printBod(u.getBodB()));
//        }
//        fw.close();