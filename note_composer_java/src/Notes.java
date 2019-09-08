package midiapp;

/**
 *
 * @author Kuba
 */
public final class Notes {
    private int id = 0;
    private int row = 0;
    private int xPos = 0;
    private int yPos = 0;
    private byte type = 0;
    private byte halfNote = 0;
    private boolean dotted = false;
    private boolean barEnd = false;
    private float maxMeasure = 0.0f;
    private int[] restPos = {10, 9, 9, 10, 10};
    private char[] staff = {'B', 'C', 'D', 'E', 'F', 'G', 'A', 'B', 'C'};
    private String jFugue = "";
    public String[] jFugueRow = {"A6", "G6", "F6", "E6", "D6", "C6", "B5", "A5", "G5", "F5", "E5", "D5", "C5", "B4", "A4", "G4", "F4"};
    public String[] jFugueType = {"w", "h", "q", "i", "s", "Rw", "Rh", "Rq", "Ri", "Rs"};

    public Notes(int id, int row, int xPos, int yPos, byte type, byte halfNote, boolean barEnd, boolean dotted, float maxMeasure) {
        this.id = id;
        this.row = row;
        this.xPos = xPos;
        this.yPos = yPos;
        this.type = type;
        this.halfNote = halfNote;
        this.barEnd = barEnd;
        this.dotted = dotted;
        this.maxMeasure = maxMeasure;
        if (type >= 5) this.yPos = restPos[type - 5];
        setJFugue();
    }

    public Notes() {
    }

    public void setId(int id) {
        this.id = id;
    }

    public void setRow(int row) {
        this.row = row;
    }

    public void setXPos(int xPos) {
        this.xPos = xPos;
    }

    public void setYPos(int yPos) {
        this.yPos = yPos;
    }

    public void setType(byte type) {
        this.type = type;
    }

    public void setHalfNote(byte halfNote) {
        this.halfNote = halfNote;
    }

    public void setBarEnd(boolean barEnd) {
        this.barEnd = barEnd;
    }

    public void setDotted(boolean dotted) {
        this.dotted = dotted;
    }

    public void setMaxMeasure(float measure) {
        maxMeasure = measure;
    }

    public void setJFugue() {
        if (type < 5) {
            this.jFugue = jFugueRow[yPos];
            if (halfNote == 3 || halfNote == 1)
                this.jFugue = this.jFugue.substring(0, 1) + "#" + this.jFugue.substring(1);
            if (halfNote == 1) {
                int num = this.jFugue.charAt(2);
                //v případě flat snižujeme o půltón
                for (int i = 1; i <= 7; i++) {
                    if (this.jFugue.charAt(0) == staff[i]) {
                        if (i == 1) this.jFugue = this.jFugue.substring(0, 2) + (char)(num - 1) + this.jFugue.substring(3);
                        this.jFugue = staff[i - 1] + this.jFugue.substring(1);
                        break;
                    }
                }
            }
            this.jFugue += jFugueType[type];
            if (dotted) this.jFugue += ".";
        }
        else this.jFugue = jFugueType[type];
    }

    public int getId() {
        return id;
    }

    public int getRow() {
        return row;
    }

    public int getXPos() {
        return xPos;
    }

    public int getYPos() {
        return yPos;
    }

    public byte getType() {
        return type;
    }

    public byte getHalfNote() {
        return halfNote;
    }

    public boolean getBarEnd() {
        return barEnd;
    }

    public boolean getDotted() {
        return dotted;
    }

    public float getMaxMeasure() {
        return maxMeasure;
    }

    public String getJFugue() {
        return jFugue;
    }

}
