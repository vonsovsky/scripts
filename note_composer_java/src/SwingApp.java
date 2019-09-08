package midiapp;

import java.awt.event.*;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.sound.midi.InvalidMidiDataException;
import javax.swing.*;
import java.awt.*;
import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.File;
import java.io.FileReader;
import java.io.FileWriter;
import java.util.Iterator;
import javax.swing.filechooser.FileNameExtensionFilter;
import org.jfugue.Pattern;
import org.jfugue.Player;

/**
 *
 * @author Kuba
 */
public class SwingApp extends JFrame implements ActionListener, MouseListener, KeyListener, MouseWheelListener {

    JToggleButton whole_note = new JToggleButton();
    JToggleButton half_note = new JToggleButton();
    JToggleButton quarter_note = new JToggleButton("", true);
    JToggleButton eighth_note = new JToggleButton();
    JToggleButton sixteenth_note = new JToggleButton();
    JToggleButton whole_rest = new JToggleButton();
    JToggleButton half_rest = new JToggleButton();
    JToggleButton quarter_rest = new JToggleButton();
    JToggleButton eighth_rest = new JToggleButton();
    JToggleButton sixteenth_rest = new JToggleButton();
    JToggleButton clear = new JToggleButton("", true);
    JToggleButton flat = new JToggleButton();
    JToggleButton natural = new JToggleButton();
    JToggleButton sharp = new JToggleButton();
    JToggleButton undot = new JToggleButton("", true);
    JToggleButton dot = new JToggleButton();
    JTextField tempo = new JTextField(3);
    JSpinner volume = new JSpinner(new SpinnerNumberModel(100, 0, 100, 5));
    String[] instruments = {"ACOUSTIC_GRAND", "BRIGHT_ACOUSTIC", "ELECTRIC_GRAND", "HONKEY_TONK", "ELECTRIC_PIANO", "ELECTRIC_PIANO_2",
                            "HARPISCHORD", "CLAVINET", "CELESTA", "GLOCKENSPIEL", "MUSIC_BOX", "VIBRAPHONE", "MARIMBA", "XYLOPHONE",
                            "TUBULAR_BELLS", "DULCIMER", "DRAWBAR_ORGAN", "PERCUSSIVE_ORGAN", "ROCK_ORGAN", "CHURCH_ORGAN",
                            "REED_ORGAN", "ACCORDIAN", "HARMONICA", "TANGO_ACCORDIAN", "GUITAR", "NYLON_STRING_GUITAR",
                            "STEEL_STRING_GUITAR", "ELECTRIC_JAZZ_GUITAR", "ELECTRIC_CLEAN_GUITAR", "ELECTRIC_MUTED_GUITAR",
                            "OVERDRIVEN_GUITAR", "DISTORTION_GUITAR", "GUITAR_HARMONICS", "ACOUSTIC_BASS", "ELECTRIC_BASS_FINGER",
                            "ELECTRIC_BASS_PICK", "FRETLESS_BASS", "SLAP_BASS_1", "SLAP_BASS_2", "SYNTH_BASS_1", "SYNTH_BASS_2",
                            "VIOLIN", "VIOLA", "CELLO", "CONTRABASS", "TREMOLO_STRINGS", "PIZZICATO_STRINGS", "ORCHESTRAL_STRINGS",
                            "TIMPANI", "STRING_ENSEMBLE_1", "STRING_ENSEMBLE_2", "SYNTHSTRINGS_1", "SYNTHSTRINGS_2", "CHOIR_AAHS",
                            "VOICE_OOHS", "SYNTH_VOICE", "ORCHESTRA_HIT", "TRUMPET", "TROMBONE", "TUBA", "MUTED_TRUMPET", "FRENCH_HORN",
                            "BRASS_SECTION", "SYNTHBRASS_1", "SYNTHBRASS_2", "SOPRANO_SAX", "ALTO_SAX", "TENOR_SAX", "BARITONE_SAX",
                            "OBOE", "ENGLISH_HORN", "BASSOON", "CLARINET", "PICCOLO", "FLUTE", "RECORDER", "PAN_FLUTE", "BLOWN_BOTTLE",
                            "SKAKUHACHI", "WHISTLE", "OCARINA", "SQUARE", "SAWTOOTH", "CALLIOPE", "CHIFF", "CHARANG", "VOICE", "BASSLEAD",
                            "NEW_AGE", "WARM", "POLYSYNTH", "CHOIR", "BOWED", "METALLIC", "HALO", "SWEEP", "RAIN", "SOUNDTRACK", "CRYSTAL",
                            "ATMOSPHERE", "BRIGHTNESS", "GOBLINS", "ECHOES", "SCI-FI", "SITAR", "BANJO", "SHAMISEN", "KOTO", "KALIMBA",
                            "BAGPIPE", "FIDDLE", "SHANAI", "TINKLE_BELL", "AGOGO", "STEEL_DRUMS", "WOODBLOCK", "TAIKO_DRUM", "MELODIC_TOM",
                            "SYNTH_DRUM", "REVERSE_CYMBAL", "GUITAR_FRET_NOISE", "BREATH_NOISE", "SEASHORE", "BIRD_TWEET", "TELEPHONE_RING",
                            "HELICOPTER", "APPLAUSE", "GUNSHOT"};
    String[] numerators = {"1", "2", "3", "4", "5", "6", "8", "9", "12"};
    String[] denominators = {"1", "2", "4", "8", "16", "32"};
    JComboBox instrument = new JComboBox(instruments);
    JComboBox numerator = new JComboBox(numerators);
    JComboBox denominator = new JComboBox(denominators);
    public float currentM = 0.0f;
    public float measure = 1.0f;
    JButton play = new JButton("Přehrát");
    JButton newFile = new JButton("Nový");
    JButton save = new JButton("Uložit");
    JButton load = new JButton("Načíst");
    JButton saveMidi = new JButton("Uložit MIDI");
    JMenuBar menuBar;
    JMenu menu_file, menu_help;
    JMenuItem menuItem_new, menuItem_play, menuItem_close, menuItem_help, menuItem_about, menuItem_save, menuItem_load, menuItem_saveMidi;
    JFileChooser chooser = new JFileChooser();
    Container cp, c;
    private Player player = new Player();

    final JFileChooser fc = new JFileChooser();

    MidiApp mapp = new MidiApp();

    public SwingApp() {
        GraphicsEnvironment ge = GraphicsEnvironment.getLocalGraphicsEnvironment();
        setDefaultLookAndFeelDecorated(true);
        setTitle("Midi Aplikace");
        setSize(1014, 650);
        setLocation(ge.getCenterPoint().x - 507, ge.getCenterPoint().y - 325);
        setResizable(false);

        addWindowListener(new WindowAdapter() {

            @Override
            public void windowClosing(WindowEvent e) {
                System.exit(0);
            }
        });


        ButtonGroup group = new ButtonGroup();
        ButtonGroup halfTones = new ButtonGroup();
        ButtonGroup dotted = new ButtonGroup();

        c = getContentPane();
        c.setLayout(new BorderLayout());

        c.add(whole_note);
        whole_note.setLocation(5, 5);
        whole_note.setSize(25, 42);
        whole_note.setIcon(new ImageIcon("images/wholenote-icon.gif"));
        whole_note.setVisible(true);
        whole_note.addActionListener(this);
        whole_note.setMargin(new Insets(0, 0, 0, 0));
        group.add(whole_note);

        c.add(half_note);
        half_note.setLocation(43, 5);
        half_note.setSize(25, 42);
        half_note.setIcon(new ImageIcon("images/halfnote-icon.gif"));
        half_note.setVisible(true);
        half_note.addActionListener(this);
        half_note.setMargin(new Insets(0, 0, 0, 0));
        group.add(half_note);

        c.add(quarter_note);
        quarter_note.setIcon(new ImageIcon("images/quarternote-icon.gif"));
        quarter_note.setLocation(81, 5);
        quarter_note.setSize(25, 42);
        quarter_note.setVisible(true);
        quarter_note.addActionListener(this);
        quarter_note.setMargin(new Insets(0, 0, 0, 0));
        group.add(quarter_note);

        c.add(eighth_note);
        eighth_note.setIcon(new ImageIcon("images/eighthnote-icon.gif"));
        eighth_note.setLocation(119, 5);
        eighth_note.setSize(25, 42);
        eighth_note.setVisible(true);
        eighth_note.addActionListener(this);
        eighth_note.setMargin(new Insets(0, 0, 0, 0));
        group.add(eighth_note);

        c.add(sixteenth_note);
        sixteenth_note.setIcon(new ImageIcon("images/sixteenthnote-icon.gif"));
        sixteenth_note.setLocation(157, 5);
        sixteenth_note.setSize(25, 42);
        sixteenth_note.setVisible(true);
        sixteenth_note.addActionListener(this);
        sixteenth_note.setMargin(new Insets(0, 0, 0, 0));
        group.add(sixteenth_note);

        JSeparator sep1 = new JSeparator(JSeparator.VERTICAL);
        c.add(sep1);
        sep1.setLocation(195, 5);
        sep1.setSize(3, 42);
        sep1.setVisible(true);

        c.add(whole_rest);
        whole_rest.setIcon(new ImageIcon("images/wholerest-icon.gif"));
        whole_rest.setLocation(209, 5);
        whole_rest.setSize(25, 42);
        whole_rest.setVisible(true);
        whole_rest.addActionListener(this);
        whole_rest.setMargin(new Insets(0, 0, 0, 0));
        group.add(whole_rest);

        c.add(half_rest);
        half_rest.setIcon(new ImageIcon("images/halfrest-icon.gif"));
        half_rest.setLocation(247, 5);
        half_rest.setSize(25, 42);
        half_rest.setVisible(true);
        half_rest.addActionListener(this);
        half_rest.setMargin(new Insets(0, 0, 0, 0));
        group.add(half_rest);

        c.add(quarter_rest);
        quarter_rest.setIcon(new ImageIcon("images/quarterrest-icon.gif"));
        quarter_rest.setLocation(285, 5);
        quarter_rest.setSize(25, 42);
        quarter_rest.setVisible(true);
        quarter_rest.addActionListener(this);
        quarter_rest.setMargin(new Insets(0, 0, 0, 0));
        group.add(quarter_rest);

        c.add(eighth_rest);
        eighth_rest.setIcon(new ImageIcon("images/eighthrest-icon.gif"));
        eighth_rest.setLocation(323, 5);
        eighth_rest.setSize(25, 42);
        eighth_rest.setVisible(true);
        eighth_rest.addActionListener(this);
        eighth_rest.setMargin(new Insets(0, 0, 0, 0));
        group.add(eighth_rest);

        c.add(sixteenth_rest);
        sixteenth_rest.setIcon(new ImageIcon("images/sixteenthrest-icon.gif"));
        sixteenth_rest.setLocation(361, 5);
        sixteenth_rest.setSize(25, 42);
        sixteenth_rest.setVisible(true);
        sixteenth_rest.addActionListener(this);
        sixteenth_rest.setMargin(new Insets(0, 0, 0, 0));
        group.add(sixteenth_rest);

        JSeparator sep2 = new JSeparator(JSeparator.VERTICAL);
        c.add(sep2);
        sep2.setLocation(399, 5);
        sep2.setSize(3, 42);
        sep2.setVisible(true);

        c.add(clear);
        clear.setIcon(new ImageIcon("images/clear-icon.gif"));
        clear.setLocation(413, 5);
        clear.setSize(25, 42);
        clear.setVisible(true);
        clear.addActionListener(this);
        clear.setMargin(new Insets(0, 0, 0, 0));
        halfTones.add(clear);

        c.add(flat);
        flat.setIcon(new ImageIcon("images/flat-icon.gif"));
        flat.setLocation(451, 5);
        flat.setSize(25, 42);
        flat.setVisible(true);
        flat.addActionListener(this);
        flat.setMargin(new Insets(0, 0, 0, 0));
        halfTones.add(flat);

        c.add(natural);
        natural.setIcon(new ImageIcon("images/natural-icon.gif"));
        natural.setLocation(489, 5);
        natural.setSize(25, 42);
        natural.setVisible(true);
        natural.addActionListener(this);
        natural.setMargin(new Insets(0, 0, 0, 0));
        halfTones.add(natural);

        c.add(sharp);
        sharp.setIcon(new ImageIcon("images/sharp-icon.gif"));
        sharp.setLocation(527, 5);
        sharp.setSize(25, 42);
        sharp.setVisible(true);
        sharp.addActionListener(this);
        sharp.setMargin(new Insets(0, 0, 0, 0));
        halfTones.add(sharp);

        JSeparator sep3 = new JSeparator(JSeparator.VERTICAL);
        c.add(sep3);
        sep3.setLocation(565, 5);
        sep3.setSize(3, 42);
        sep3.setVisible(true);

        c.add(undot);
        undot.setIcon(new ImageIcon("images/clear-icon.gif"));
        undot.setLocation(579, 5);
        undot.setSize(25, 42);
        undot.setVisible(true);
        undot.addActionListener(this);
        undot.setMargin(new Insets(0, 0, 0, 0));
        dotted.add(undot);

        c.add(dot);
        dot.setIcon(new ImageIcon("images/dot-icon.gif"));
        dot.setLocation(617, 5);
        dot.setSize(25, 42);
        dot.setVisible(true);
        dot.addActionListener(this);
        dot.setMargin(new Insets(0, 0, 0, 0));
        dotted.add(dot);

        JSeparator sep4 = new JSeparator(JSeparator.VERTICAL);
        c.add(sep4);
        sep4.setLocation(656, 5);
        sep4.setSize(3, 42);
        sep4.setVisible(true);

        c.add(newFile);
        newFile.setLocation(669, 5);
        newFile.setSize(50, 42);
        newFile.setVisible(true);
        newFile.setMargin(new Insets(0, 0, 0, 0));
        newFile.addActionListener(this);

        c.add(play);
        play.setLocation(732, 5);
        play.setSize(60, 42);
        play.setVisible(true);
        play.setMargin(new Insets(0, 0, 0, 0));
        play.addActionListener(this);

        c.add(save);
        save.setLocation(805, 5);
        save.setSize(50, 42);
        save.setVisible(true);
        save.setMargin(new Insets(0, 0, 0, 0));
        save.addActionListener(this);

        c.add(load);
        load.setLocation(868, 5);
        load.setSize(50, 42);
        load.setVisible(true);
        load.setMargin(new Insets(0, 0, 0, 0));
        load.addActionListener(this);

        c.add(saveMidi);
        saveMidi.setLocation(931, 5);
        saveMidi.setSize(74, 42);
        saveMidi.setVisible(true);
        saveMidi.setMargin(new Insets(0, 0, 0, 0));
        saveMidi.addActionListener(this);

        c.add(tempo);
        tempo.setLocation(5, 54);
        tempo.setSize(30, 20);
        tempo.setVisible(true);
        tempo.setText("120");

        JTextArea text1 = new JTextArea();
        c.add(text1);
        text1.setLocation(40, 55);
        text1.setSize(40, 20);
        text1.setVisible(true);
        text1.setBackground(getBackground());
        text1.setFont(new Font("Dialog", Font.BOLD, 12));
        text1.setText("Tempo");

        c.add(numerator);
        numerator.setLocation(183, 54);
        numerator.setSize(40, 20);
        numerator.setSelectedItem("4");
        numerator.setVisible(false);

        JTextArea text2 = new JTextArea();
        c.add(text2);
        text2.setLocation(228, 55);
        text2.setSize(70, 20);
        text2.setVisible(true);
        text2.setBackground(getBackground());
        text2.setFont(new Font("Dialog", Font.BOLD, 12));
        text2.setText("Numerator");

        c.add(denominator);
        denominator.setLocation(328, 54);
        denominator.setSize(40, 20);
        denominator.setSelectedItem("4");
        denominator.setVisible(false);

        JTextArea text3 = new JTextArea();
        c.add(text3);
        text3.setLocation(373, 55);
        text3.setSize(73, 20);
        text3.setVisible(true);
        text3.setBackground(getBackground());
        text3.setFont(new Font("Dialog", Font.BOLD, 12));
        text3.setText("Denominator");

        c.add(volume);
        volume.setLocation(549, 54);
        volume.setSize(40, 20);
        volume.setVisible(true);

        JTextArea text4 = new JTextArea();
        c.add(text4);
        text4.setLocation(594, 55);
        text4.setSize(60, 20);
        text4.setVisible(true);
        text4.setBackground(getBackground());
        text4.setFont(new Font("Dialog", Font.BOLD, 12));
        text4.setText("Hlasitost");

        c.add(instrument);
        instrument.setLocation(757, 54);
        instrument.setSize(200, 20);
        instrument.setVisible(true);

        JTextArea text5 = new JTextArea();
        c.add(text5);
        text5.setLocation(962, 55);
        text5.setSize(60, 20);
        text5.setVisible(true);
        text5.setBackground(getBackground());
        text5.setFont(new Font("Dialog", Font.BOLD, 12));
        text5.setText("Nástroj");

        menuBar = new JMenuBar();
        menu_file = new JMenu("Soubor");
        menu_file.setMnemonic(KeyEvent.VK_S);
        //Nový
        menuItem_new = new JMenuItem("Nové", KeyEvent.VK_N);
        menuItem_new.addActionListener(this);
        menu_file.add(menuItem_new);
        //Přehrát
        menuItem_play = new JMenuItem("Přehrát", KeyEvent.VK_P);
        menuItem_play.addActionListener(this);
        menu_file.add(menuItem_play);
        //Uložit
        menuItem_save = new JMenuItem("Uložit", KeyEvent.VK_U);
        menuItem_save.addActionListener(this);
        menu_file.add(menuItem_save);
        // Načíst
        menuItem_load = new JMenuItem("Načíst", KeyEvent.VK_T);
        menuItem_load.addActionListener(this);
        menu_file.add(menuItem_load);
        // Export do MIDI
        menuItem_saveMidi = new JMenuItem("Export do MIDI", KeyEvent.VK_E);
        menuItem_saveMidi.addActionListener(this);
        menu_file.add(menuItem_saveMidi);

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

        mapp.dp.ImagePanel();

        cp = getContentPane();
        cp.add(mapp.dp);

        cp.addMouseListener(this);
        cp.addKeyListener(this);
        cp.setFocusable(true);
    }

    private void redraw() {
        mapp.dp.repaint();
        c.repaint();
    }

    private void deleteNote() {
        if (mapp.dp.noteCount > 0) {
            if (mapp.dp.noteCount % 48 == 0) {
                mapp.dp.row = 3;
                mapp.dp.list = mapp.dp.noteCount / 48 - 1;
            }

            Notes n = null;
            mapp.dp.noteCount--;

            n = mapp.dp.notes.get(mapp.dp.noteCount);
            if (n.getBarEnd()) currentM = n.getMaxMeasure();
            currentM -= 1 / Math.pow(2, n.getType() % 5);
            if (currentM < 0) currentM = 0;
            mapp.dp.notes.remove(n);
            if (mapp.dp.noteCount % 16 == 15)
                mapp.dp.row--;
            redraw();
        }
    }

    private void addNote(int row) {
        boolean barEnd = false;

        numerator.setEnabled(false);
        denominator.setEnabled(false);
        measure = Float.parseFloat(numerator.getSelectedItem().toString()) / Float.parseFloat(denominator.getSelectedItem().toString());
        currentM += (mapp.dp.dotted)? (1 / Math.pow(2, mapp.dp.noteType % 5)) * 1.5f : 1 / Math.pow(2, mapp.dp.noteType % 5);

        if (currentM >= measure)
            barEnd = true;
        mapp.dp.notes.add(new
                Notes(mapp.dp.noteCount, mapp.dp.row, mapp.dp.noteCount % 16, row, mapp.dp.noteType, mapp.dp.halfNote, barEnd, mapp.dp.dotted, currentM));
        if (currentM >= measure)
            currentM = 0.0f;

        mapp.dp.noteCount++;
        if (mapp.dp.noteCount % 16 == 0)
            mapp.dp.row++;
        if (mapp.dp.noteCount % 48 == 0)
            mapp.dp.row = 0;
        mapp.dp.list = mapp.dp.noteCount / 48;
        redraw();
    }

    private void newFile() {
        numerator.setEnabled(true);
        denominator.setEnabled(true);
        currentM = 0.0f;

        mapp.dp.notes.clear();
        mapp.dp.noteCount = 0;
        mapp.dp.row = 0;
        mapp.dp.list = 0;
        redraw();
    }

    private String buildPattern() {
        Notes n = null;

        String pattern = "X[Volume]=" + (int)(Integer.parseInt(volume.getValue().toString()) * 163.83) + " ";
        pattern += "T" + tempo.getText() + " ";
        pattern += "I[" + instrument.getSelectedItem() + "] ";

        Iterator itr = mapp.dp.notes.iterator();
        while (itr.hasNext()) {
            n = (Notes) itr.next();
            pattern += n.getJFugue() + " ";
        }

        return pattern;
    }

    private void play() {
        String pattern = buildPattern();
        player.play(pattern);
    }

    public void saveMidi() {
        chooser.addChoosableFileFilter(new FileNameExtensionFilter("Soubory MIDI", new String[] {"mid", "midi"}));
        int returnVal = chooser.showDialog(this, "Uložit jako MIDI");
        if (returnVal != JFileChooser.APPROVE_OPTION)
            return;
        File file = chooser.getSelectedFile();
        if (!file.getName().endsWith(".mid"))
            file = new File(file.getAbsolutePath() + ".mid");

        String pattern = buildPattern();
        try {
            player.saveMidi(pattern, file);
        } catch (IOException ex) {
            //Logger.getLogger(SwingApp.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    public void loadMidi() {
        int returnVal = chooser.showDialog(this, "Otevřít a přehrát MIDI");
        if (returnVal != JFileChooser.APPROVE_OPTION)
            return;
        File file = chooser.getSelectedFile();
        try {
            Pattern pattern = player.loadMidi(file);
            player.play(pattern);

            file = new File("C:/midiout.txt");
            try {
                BufferedWriter out = new BufferedWriter(new FileWriter(file));
                out.write(pattern.getMusicString());
                out.close();
            } catch (Exception e) { //Catch exception if any
              System.err.println("Error: " + e.getMessage());
            }
        } catch (IOException ex) {
            Logger.getLogger(SwingApp.class.getName()).log(Level.SEVERE, null, ex);
        } catch (InvalidMidiDataException ex) {
            Logger.getLogger(SwingApp.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    public void save() {
        int returnVal = chooser.showDialog(this, "Uložit");
        if (returnVal != JFileChooser.APPROVE_OPTION)
            return;
        File file = chooser.getSelectedFile();

        String pattern = "M" + numerator.getSelectedItem() + "/" + denominator.getSelectedItem() + " ";
        pattern += buildPattern();
        try {
            BufferedWriter out = new BufferedWriter(new FileWriter(file));
            out.write(pattern);
            out.close();
        } catch (Exception e) { //Catch exception if any
          System.err.println("Error: " + e.getMessage());
        }
    }

    public void load() {
        int returnVal = chooser.showDialog(this, "Otevřít");
        if (returnVal != JFileChooser.APPROVE_OPTION)
            return;
        File file = chooser.getSelectedFile();

        String singleRow = "";
        String[] noteArray = new String[20];
        int row;
        Notes n = new Notes();

        try {
            BufferedReader in = new BufferedReader(new FileReader(file));
            newFile();
            while ((singleRow = in.readLine()) != null) {
                noteArray = singleRow.split(" ");
                for (String tone : noteArray) {
                    row = -1;
                    mapp.dp.halfNote = 0;
                    mapp.dp.dotted = false;
                    tone = tone.trim();
                    if (!tone.equals("")) {
                        if (tone.charAt(0) == 'X')
                            volume.setValue(new Integer((int)Math.ceil(Integer.parseInt(tone.substring(10)) * 100 / 16383.0)));
                        else if (tone.charAt(0) == 'T') tempo.setText(tone.substring(1));
                        else if (tone.charAt(0) == 'I') instrument.setSelectedItem(tone.substring(2, tone.length() - 1));
                        else if (tone.charAt(0) == 'M') {
                            String m = tone.substring(1);
                            String[] measures = m.split("/");
                            numerator.setSelectedItem(measures[0]);
                            denominator.setSelectedItem(measures[1]);
                        }
                        else {
                            if (tone.contains("#")) {
                                mapp.dp.halfNote = 3;
                                tone = tone.substring(0, 1) + tone.substring(2);
                            }
                            if (tone.contains("."))
                                mapp.dp.dotted = true;
                            for (int i = 0; i < n.jFugueRow.length; i++) {
                                if (tone.contains(n.jFugueRow[i])) {
                                    row = i;
                                    break;
                                }
                            }
                            for (byte i = (byte) (n.jFugueType.length - 1); i >= 0; i--) {
                                if (tone.contains(n.jFugueType[i])) {
                                    mapp.dp.noteType = i;
                                    break;
                                }
                            }
                            addNote(row);
                        }
                    }
                }
            }
            mapp.dp.noteType = 2;
            mapp.dp.halfNote = 0;
            mapp.dp.dotted = false;
            in.close();
        } catch (FileNotFoundException ex) {
            Logger.getLogger(SwingApp.class.getName()).log(Level.SEVERE, null, ex);
        } catch (IOException ioex) {
            Logger.getLogger(SwingApp.class.getName()).log(Level.SEVERE, null, ioex);
        }
    }

    public void help() {
        String help = "Přehrávání pomocí notového zápisu\n\nOvládání:\nKlávesy 1-10: Noty a pauzy\n"
                + "Půltóny a jejich rušení: Home, PgUp, PgDn, End\nNový program: N\nPřehrát: P\n"
                + "Prodloužení noty o polovinu a zrušení: . a ,\n"
                + "Uložit (Save): S\nNačíst (Load): L\nUložit MIDI: M\nNotová osnova: (C, D, E, F, G, A, H/B)\n"
                + "Předchozí strana: =\nNásledující strana: ´\n"
                + "Změna délky: levá/pravá klávesa\nZměna výšky: horní/dolní klávesa\nSmazání noty: Delete/BackSpace\n"
                + "Konec programu: Escape\n\n"
                + "Klávesnice je rychlejší, ale všechny akce lze\nprovádět i za pomoci myši\n\n"
                + "Vytvořil student Jakub Vonšovský do předmětu PV121";

        JOptionPane.showMessageDialog(this, help,
                "Aplikace Midi - Nápověda", JOptionPane.INFORMATION_MESSAGE);
    }

    public void changeType(int way) {
        if (mapp.dp.noteCount == 0)
            return;
        Notes n = mapp.dp.notes.get(mapp.dp.noteCount - 1);
        mapp.dp.notes.remove(mapp.dp.noteCount - 1);
        if (way == -1)
            if (n.getType() > 0) n.setType((byte) (n.getType() - 1));
        if (way == 1)
            if (n.getType() < 4) n.setType((byte) (n.getType() + 1));
        n.setJFugue();
        mapp.dp.notes.add(n);
        redraw();
    }

    public void changeYPos(int way) {
        if (mapp.dp.noteCount == 0)
            return;
        Notes n = mapp.dp.notes.get(mapp.dp.noteCount - 1);
        mapp.dp.notes.remove(mapp.dp.noteCount - 1);
        if (way == -1)
            if (n.getYPos() > 0) n.setYPos(n.getYPos() - 1);
        if (way == 1)
            if (n.getYPos() < 14) n.setYPos(n.getYPos() + 1);
        n.setJFugue();
        mapp.dp.notes.add(n);
        redraw();
    }

    private void prevPage() {
        if (mapp.dp.list > 0) {
            mapp.dp.list--;
            redraw();
        }
    }

    private void nextPage() {
        if (mapp.dp.list < mapp.dp.noteCount / 48) {
            mapp.dp.list++;
            redraw();
        }
    }

    public void actionPerformed(ActionEvent e) {
        if (e.getSource() == whole_note) mapp.dp.noteType = 0;
        if (e.getSource() == half_note) mapp.dp.noteType = 1;
        if (e.getSource() == quarter_note) mapp.dp.noteType = 2;
        if (e.getSource() == eighth_note) mapp.dp.noteType = 3;
        if (e.getSource() == sixteenth_note) mapp.dp.noteType = 4;
        if (e.getSource() == whole_rest) mapp.dp.noteType = 5;
        if (e.getSource() == half_rest) mapp.dp.noteType = 6;
        if (e.getSource() == quarter_rest) mapp.dp.noteType = 7;
        if (e.getSource() == eighth_rest) mapp.dp.noteType = 8;
        if (e.getSource() == sixteenth_rest) mapp.dp.noteType = 9;

        if (e.getSource() == clear) mapp.dp.halfNote = 0;
        if (e.getSource() == flat) mapp.dp.halfNote = 1;
        if (e.getSource() == natural) mapp.dp.halfNote = 2;
        if (e.getSource() == sharp) mapp.dp.halfNote = 3;
        if (e.getSource() == dot) mapp.dp.dotted = true;
        if (e.getSource() == undot) mapp.dp.dotted = false;

        if (e.getSource() == newFile || e.getSource() == menuItem_new) newFile();
        if (e.getSource() == play || e.getSource() == menuItem_play) play();
        if (e.getSource() == save || e.getSource() == menuItem_save) save();
        if (e.getSource() == load || e.getSource() == menuItem_load) load();
        if (e.getSource() == saveMidi || e.getSource() == menuItem_saveMidi) saveMidi();

        if (e.getSource() == menuItem_help) help();
        if (e.getSource() == menuItem_close) System.exit(0);

        if (e.getSource() == menuItem_about) {
            JOptionPane.showMessageDialog(this, "Přehrávání notového zápisu pomocí JFugue - Jakub Vonšovský",
                    "Aplikace Midi", JOptionPane.INFORMATION_MESSAGE);
        }

        cp.requestFocusInWindow();
    }

    public void mouseClicked(MouseEvent e) {
    }

    @SuppressWarnings("static-access")
    public void mousePressed(MouseEvent e) {
        if (e.getButton() == e.BUTTON1) {
            int setRow = -1;
            for (int i = 0; i < 14; i++) {
                if (e.getY() - 85 - mapp.dp.row * 165 > mapp.dp.notePos[i] && e.getY() - 85 - mapp.dp.row * 165 < mapp.dp.notePos[i + 1]) {
                    if (e.getY() - 85 - mapp.dp.row * 165 - mapp.dp.notePos[i] < (mapp.dp.notePos[i + 1] - mapp.dp.notePos[i]) / 2)
                        setRow = i;
                    else setRow = i + 1;
                }
            }

            //if (e.getX() > 20 && e.getX() < 990 && e.getY() > 60 && e.getY() < 1000)
              //  setTitle(e.getX() + ";" + e.getY());

            if (e.getX() > 940 && e.getX() < 960 && e.getY() > 565 && e.getY() < 580)
                prevPage();
            if (e.getX() > 965 && e.getX() < 985 && e.getY() > 565 && e.getY() < 580)
                nextPage();

            cp.requestFocusInWindow();  //Focus zpět k hlavnímu oknu
            if (setRow == -1) {  //chybová hláška?
                return;
            }

            addNote(setRow);
        }
        if (e.getButton() == e.BUTTON3)
            deleteNote();
    }

    public void mouseReleased(MouseEvent e) {
    }

    public void mouseEntered(MouseEvent e) {
    }

    public void mouseExited(MouseEvent e) {
    }

    public void keyTyped(KeyEvent e) {
    }

    public void keyPressed(KeyEvent e) {
        if (e.getKeyCode() == KeyEvent.VK_1) whole_note.doClick();
        if (e.getKeyCode() == KeyEvent.VK_2) half_note.doClick();
        if (e.getKeyCode() == KeyEvent.VK_3) quarter_note.doClick();
        if (e.getKeyCode() == KeyEvent.VK_4) eighth_note.doClick();
        if (e.getKeyCode() == KeyEvent.VK_5) sixteenth_note.doClick();
        if (e.getKeyCode() == KeyEvent.VK_6) whole_rest.doClick();
        if (e.getKeyCode() == KeyEvent.VK_7) half_rest.doClick();
        if (e.getKeyCode() == KeyEvent.VK_8) quarter_rest.doClick();
        if (e.getKeyCode() == KeyEvent.VK_9) eighth_rest.doClick();
        if (e.getKeyCode() == KeyEvent.VK_0) sixteenth_rest.doClick();
        if (e.getKeyCode() == KeyEvent.VK_F1) help();
        if (e.getKeyCode() == KeyEvent.VK_F2) {
            String pattern = buildPattern();
            JOptionPane.showMessageDialog(this, pattern, "Posloupnost příkazů knihovně JFugue", JOptionPane.INFORMATION_MESSAGE);
        }
        if (e.getKeyCode() == KeyEvent.VK_HOME) clear.doClick();
        if (e.getKeyCode() == KeyEvent.VK_PAGE_UP) flat.doClick();
        if (e.getKeyCode() == KeyEvent.VK_PAGE_DOWN) natural.doClick();
        if (e.getKeyCode() == KeyEvent.VK_END) sharp.doClick();
        if (e.getKeyCode() == KeyEvent.VK_COMMA) undot.doClick();
        if (e.getKeyCode() == KeyEvent.VK_PERIOD) dot.doClick();
        if (e.getKeyCode() == KeyEvent.VK_N) {
            if (JOptionPane.showConfirmDialog(null, "Skutečně vytvořit nový list?", "Nový list", JOptionPane.YES_NO_OPTION) ==
                JOptionPane.YES_OPTION)
                newFile();
        }
        if (e.getKeyCode() == KeyEvent.VK_ESCAPE) {
            if (JOptionPane.showConfirmDialog(null, "Opustit program?", "Konec", JOptionPane.YES_NO_OPTION) ==
                JOptionPane.YES_OPTION)
                System.exit(0);
        }
        if (e.getKeyCode() == KeyEvent.VK_EQUALS)   // =
            prevPage();
        if (e.getKeyCode() == KeyEvent.VK_DEAD_ACUTE)   // ´
            nextPage();
        if (e.getKeyCode() == KeyEvent.VK_P) play();
        if (e.getKeyCode() == KeyEvent.VK_S) save();
        if (e.getKeyCode() == KeyEvent.VK_L) load();
        if (e.getKeyCode() == KeyEvent.VK_M) saveMidi();
        if (e.getKeyCode() == KeyEvent.VK_X) loadMidi();  //skrytá funkce
        if (e.getKeyCode() == KeyEvent.VK_C) addNote(12);
        if (e.getKeyCode() == KeyEvent.VK_D) addNote(11);
        if (e.getKeyCode() == KeyEvent.VK_E) addNote(10);
        if (e.getKeyCode() == KeyEvent.VK_F) addNote(9);
        if (e.getKeyCode() == KeyEvent.VK_G) addNote(8);
        if (e.getKeyCode() == KeyEvent.VK_A) addNote(7);
        if (e.getKeyCode() == KeyEvent.VK_H ||
            e.getKeyCode() == KeyEvent.VK_B) addNote(6);
        if (e.getKeyCode() == KeyEvent.VK_LEFT) changeType(-1);
        if (e.getKeyCode() == KeyEvent.VK_RIGHT) changeType(1);
        if (e.getKeyCode() == KeyEvent.VK_UP) changeYPos(-1);
        if (e.getKeyCode() == KeyEvent.VK_DOWN) changeYPos(1);
        if (e.getKeyCode() == KeyEvent.VK_DELETE || e.getKeyCode() == KeyEvent.VK_BACK_SPACE) deleteNote();
        if (e.getKeyCode() == KeyEvent.VK_Q) {



        }
    }

    public void keyReleased(KeyEvent e) {
    }

    public void mouseWheelMoved(MouseWheelEvent e) {
        int notches = e.getWheelRotation();
        if (notches < 0) prevPage();
        else nextPage();
        redraw();
    }
}
