package fi.jamk.coolcalendar;

import android.app.Application;
import android.content.Context;

public class MyStorage extends Application {

    private String userId;
    private String fullname;
    private static Context context;

    public void onCreate(){
        super.onCreate();
        MyStorage.context = getApplicationContext();
    }

    public static Context getAppContext() {
        return MyStorage.context;
    }
    
    public String getUserId() {
        return userId;
    }

    public void setUserId(String userId) {
        this.userId = userId;
    }

    public String getFullname() {
        return fullname;
    }

    public void setFullname(String fullname) {
        this.fullname = fullname;
    }

}