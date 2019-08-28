package fi.jamk.coolcalendar;

import java.io.IOException;
import java.util.ArrayList;
import java.util.Iterator;
import java.util.List;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.util.EntityUtils;
import org.json.JSONException;
import org.json.JSONObject;

import android.os.AsyncTask;
import android.util.Log;

class RequestTask extends AsyncTask<String, String, String>{

    private String callbackClass = null;
	
	String param1;
	String param2;
	String param3;
	String param4;
    
    /*
	 * Makes HTTP request
	 * param 1 is url, mandatory
	 * param 2 are json encoded post data
	 * param 3 is user header for authentication
	 * param 4 is callback class so program knows what to call after task is done
	 */
    protected String doInBackground(String... params) {
    	String url = params[0];
    	String jsonData = null;
    	List<NameValuePair> nameValuePair = null;
    	String userHeader = null;
		param1 = url;

    	if (params.length >= 2) {
    		jsonData = params[1];
    	}
    	if (jsonData != null) {
    		nameValuePair = decodeJsonData(jsonData);
    	}
    	if (params.length >= 3) {
    		userHeader = params[2];
    		param3 = userHeader;
    	}
    	if (params.length >= 4) {
    		callbackClass = params[3];
    		param4 = callbackClass;
    	}
    	
    	// Create a new HttpClient and Post Header
	    HttpClient httpclient = new DefaultHttpClient();
	    HttpPost httppost = new HttpPost(url);
	    String responseBody;
	    if (jsonData != null) {
	    	param2 = jsonData;
	    }

	    try {
	    	/*if (userHeader != null) {
	    		httppost.addHeader("Authorization", userHeader);
	    	}*/
	    	if (nameValuePair != null) {
	    		httppost.setEntity(new UrlEncodedFormEntity(nameValuePair));
	    	}

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
    
    private List<NameValuePair> decodeJsonData(String jsonData) {
    	List<NameValuePair> nameValuePair = new ArrayList<NameValuePair>();
    	
        try {
	    	JSONObject jsonObject = new JSONObject(jsonData);
	    	Iterator<String> iter = jsonObject.keys();
	        while (iter.hasNext()) {
	            String key = iter.next();
	            String value = (String) jsonObject.get(key);
	            Log.v("keys", key + ":" + value);
	            nameValuePair.add(new BasicNameValuePair(key, value));
	        }
        } catch (JSONException e) {
        	// this should not happen normally so there is no 
        	// need (hopefully) to return this message to user
        	Log.v("Error", "Malformed JSON: " + e.getMessage());
        }
    	
    	return nameValuePair;
    }

    @Override
    protected void onPostExecute(String result) {
        super.onPostExecute(result);
        DBService.httpResult = result;
        //DBService.httpResult = param1 + ":" + param2 + ":" + param3 + ":" + param4;
        DBService.returnHttpData(callbackClass);
    }
}
