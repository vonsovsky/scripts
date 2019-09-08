package fi.jamk.coolcalendar;

import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.lang.reflect.InvocationTargetException;
import java.lang.reflect.Method;
import java.util.List;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.util.EntityUtils;
import org.apache.http.NameValuePair;
import org.json.JSONException;
import org.json.JSONObject;
import android.util.Base64;
import android.util.Log;

public class DBService {

	public static String urlPrefix = "http://shrouded-shelf-3037.herokuapp.com/";
	//public static String urlPrefix = "http://localhost:8080/";
	public static String httpResult;
	
	/*
	 * Returns Basic access authentication header
	 */
	public static String getAuthHeader(String username, String password) {
		// String to be encoded with Base64
		String text = username + ":" + password;
		// Sending side
		byte[] data = null;
		try {
		    data = text.getBytes("UTF-8");
		} catch (UnsupportedEncodingException e) {
		    Log.v("Encoding error: ", e.getMessage());
		}

		String base64 = Base64.encodeToString(data, Base64.DEFAULT);
		
		return "Basic " + base64;
	}
	
	public static String createUser(String fullname, String username, String password, String email) {
        // Add your data
		JSONObject jsonObj = new JSONObject();
		try {
			jsonObj.put("fullname", fullname);
			jsonObj.put("username", username);
			jsonObj.put("password", password);
			jsonObj.put("email", email);
		} catch (JSONException e) {
			return e.getMessage();
		}

        //String retString = postData(urlPrefix + "update_user", nameValuePairs);
        new RequestTask().execute(urlPrefix + "create_user", jsonObj.toString(), null, "CreateUserActivity");
        //return retString;
        return "";
	}
	
	public static void logInUser(String username, String password) {
        new RequestTask().execute(urlPrefix + "log_in", null, getAuthHeader(username, password), "LogInUserActivity");
	}

	public static String createCourse(String userId, String name, String day, String extraInfo, String start, String end) {
        // Add your data
		JSONObject jsonObj = new JSONObject();
		try {
			jsonObj.put("user", userId);
			jsonObj.put("name", name);
			jsonObj.put("day", day);
			jsonObj.put("extra_info", extraInfo);
			jsonObj.put("start", start);
			jsonObj.put("end", end);
		} catch (JSONException e) {
			return e.getMessage();
		}

        new RequestTask().execute(urlPrefix + "create_course", jsonObj.toString(), null, "CreateCourseActivity");

        return "";
	}
	
	// calls callback method in predefined class
	public static void returnHttpData(String callbackClass) {
		if (callbackClass != null) {
			try {
				Class[] cArg = new Class[1];
				cArg[0] = String.class;
				Class c = Class.forName("fi.jamk.coolcalendar." + callbackClass);
				Method m = c.getMethod("httpCallback", cArg);
				Log.v("httpCallback", "present");
				m.invoke(null, httpResult);
			} catch (ClassNotFoundException e) {
				Log.v("Class exception: ", e.getMessage());
			} catch (NoSuchMethodException e) {
				Log.v("Method exception: ", e.getMessage());
			} catch (IllegalArgumentException e) {
				Log.v("Method exception: ", e.getMessage());
			} catch (IllegalAccessException e) {
				Log.v("Method exception: ", e.getMessage());
			} catch (InvocationTargetException e) {
				Log.v("Method exception: ", e.getMessage());
			}
			//CreateUserActivity.doNotification(httpResult);
		}
	}
	
	public static String postData(String url, List<NameValuePair> postData) {
	    // Create a new HttpClient and Post Header
	    HttpClient httpclient = new DefaultHttpClient();
	    HttpPost httppost = new HttpPost(url);
	    String responseBody;

	    try {
	        httppost.setEntity((HttpEntity) new UrlEncodedFormEntity(postData));

	        // Execute HTTP Post Request
	        HttpResponse response = httpclient.execute(httppost);
	        HttpEntity entity = response.getEntity();
	        responseBody = EntityUtils.toString(entity);
	        
	    } catch (ClientProtocolException e) {
	    	responseBody = e.getMessage();
	    } catch (IOException e) {
	        responseBody = e.getMessage();
	    }
	    
	    return responseBody;
	} 

}
