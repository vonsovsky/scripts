/*
 *  Jakub Vonsovsky
 *  Skeleton from nehe.gamedev.net
 */


#include <windows.h>		// Header File For Windows
#include <math.h>			// Math Library Header File
#include <stdio.h>			// Header File For Standard Input/Output
#include <gl\gl.h>			// Header File For The OpenGL32 Library
#include <gl\glu.h>			// Header File For The GLu32 Library

//typedef bool int;

HDC			hDC=NULL;		// Private GDI Device Context
HGLRC		hRC=NULL;		// Permanent Rendering Context
HWND		hWnd=NULL;		// Holds Our Window Handle
HINSTANCE	hInstance;		// Holds The Instance Of The Application


BOOL	keys[256];			// Array Used For The Keyboard Routine
BOOL	active=TRUE;		// Window Active Flag Set To TRUE By Default
BOOL	fullscreen=FALSE;	// Fullscreen Flag Set To Fullscreen Mode By Default

const float piover180 = 0.0174532925f;

const float i = 85;
const float M1 = 0.6f;
const float M2 = 0.1f;
const float L1 = 0.6f;
const float L2 = 0.2f;
float R = 0.4f;
float R1 = 0.2f;
float R2 = 0.15f;

const float K = 3.0f;

int width = 640;
int height = 480;

const int scale = 180;
int graph[scale];
int graph_inc = 0;

float q;

float x;
float y;
float z;
float x_1;
float y_1;
float z_1;
float x_2;
float y_2;
float z_2;

float ro;
float theta1;
float theta2;
float deltaA1;
float deltaA2;

float iMax = 0;
float iMin = 0;
float pixelHeight = 0;

GLUquadricObj	*quad;

GLfloat theta = 0.0f;

LRESULT	CALLBACK WndProc(HWND, UINT, WPARAM, LPARAM);	// Declaration For WndProc

GLvoid calcInit(GLvoid) {
	q = M2 / M1;

	float div = 2.0f / R;

	R *= div;
	R1 *= div;
	R2 *= div;
}

GLvoid calcCoords(GLvoid) {
	x = R * sin(theta * piover180);
	y = R * cos(i * piover180) * cos(theta * piover180);
	z = R * sin(i * piover180) * cos(theta * piover180);

	x_1 = -x / (1 + 1 / q);
	y_1 = -y / (1 + 1 / q);
	z_1 = -z / (1 + 1 / q);

	x_2 = x / (1 + q);
	y_2 = y / (1 + q);
	z_2 = z / (1 + q);
}

GLfloat calcPhysics(GLvoid) {
	ro = sqrt(pow(x_2 - x_1, 2) + pow(y_2 - y_1, 2));

    theta1 = acos((ro * ro + R1 * R1 - R2 * R2) / (2 * R1 * ro)) / piover180 * 2;
    theta2 = acos((ro * ro + R2 * R2 - R1 * R1) / (2 * R2 * ro)) / piover180 * 2;
    deltaA1 = 0.5 * R1 * R1 * (theta1 - sin(theta1 * piover180));
    deltaA2 = 0.5 * R2 * R2 * (theta2 - sin(theta2 * piover180));

	float A1;
	float A2;

    // no eclipse
    if (ro > R1 + R2) {
      if (z_1 > z_2) {
        A1 = M_PI * R1 * R1;
        A2 = M_PI * R2 * R2;
      } else {
        A1 = M_PI * R1 * R1;
        A2 = M_PI * R2 * R2;
      }
    }

    // shallow eclipse
    if (R1 + R2 > ro && ro > sqrt(R1 * R1 - R2 * R2) && (int)theta % 360 < 180 ||
		sqrt(R1 * R1 - R2 * R2) > ro && ro > R1 - R2 && (int)theta % 360 >= 180) {
		if (z_1 > z_2) {
	        A1 = M_PI * R1 * R1;
	        A2 = M_PI * R2 * R2 - deltaA1 - deltaA2;
	    } else {
	        A1 = M_PI * R2 * R2 - deltaA1 - deltaA2;
	        A2 = M_PI * R2 * R2;
		}
    }

    // deep eclipse
    if (sqrt(R1 * R1 - R2 * R2) > ro && ro > R1 - R2 && (int)theta % 360 < 180 ||
		R1 + R2 > ro && ro > sqrt(R1 * R1 - R2 * R2) && (int)theta % 360 >= 180) {
      if (z_1 > z_2) {
        A1 = M_PI * R1 * R1;
        A2 = deltaA2 - deltaA1;
      } else {
        A1 = M_PI * R1 * R1 - M_PI * R2 * R2 + deltaA2 - deltaA1;
        A2 = M_PI * R2 * R2;
      }
    }

    // annular or total eclipse
    if (ro < R1 - R2) {
      if (z_1 > z_2) {
        A1 = M_PI * R1 * R1;
        A2 = 0;
      } else {
        A1 = M_PI * R1 * R1 - M_PI * R2 * R2;
        A2 = M_PI * R2 * R2;
      }
    }

	float I = K / 4 / M_PI * (L1 * A1 / pow(R1, 2) + L2 * A2 / pow(R2, 2));

	return I;
}

GLvoid calcGraph(GLvoid) {
	RECT	rect;														// Holds Coordinates Of A Rectangle

	GetClientRect(hWnd, &rect);								// Get Window Dimensions
	int window_height = rect.bottom-rect.top;					// Calculate The Height (Bottom-Top)

	graph_inc = 0;
	for (theta = 0.0f; theta < 180.0f; theta += 180 / (float)scale) {
		calcCoords();
		float I = calcPhysics();
		graph[graph_inc] = I;

		if (iMax < graph[graph_inc])
			iMax = graph[graph_inc];
		if (iMin > graph[graph_inc])
			iMin = graph[graph_inc];

		graph_inc++;
	}
	graph_inc = 0;

	pixelHeight = (iMax - iMin) / window_height * 4 * 2;
}

GLvoid ReSizeGLScene(GLsizei width, GLsizei height)		// Resize And Initialize The GL Window
{
	if (height==0)										// Prevent A Divide By Zero By
	{
		height=1;										// Making Height Equal One
	}

	glViewport(0, 0, width, height);						// Reset The Current Viewport

	glMatrixMode(GL_PROJECTION);						// Select The Projection Matrix
	glLoadIdentity();									// Reset The Projection Matrix

	// Calculate The Aspect Ratio Of The Window
	gluPerspective(45.0f,(GLfloat)width/(GLfloat)height,0.1f,100.0f);

	glMatrixMode(GL_MODELVIEW);							// Select The Modelview Matrix
	glLoadIdentity();									// Reset The Modelview Matrix
}

int InitGL(GLvoid)										// All Setup For OpenGL Goes Here
{
	calcInit();
	calcGraph();

	glClearColor(0.0f, 0.0f, 0.0f, 0.0f);				// This Will Clear The Background Color To Black
	glClearDepth(1.0);									// Enables Clearing Of The Depth Buffer
	glDepthFunc(GL_LEQUAL);								// The Type Of Depth Test To Do
	glEnable(GL_DEPTH_TEST);							// Enables Depth Testing
	glShadeModel(GL_SMOOTH);							// Enables Smooth Color Shading
	glHint(GL_PERSPECTIVE_CORRECTION_HINT, GL_NICEST);	// Really Nice Perspective Calculations
	//glEnable(GL_ALPHA);
	glBlendFunc(GL_SRC_ALPHA, GL_ZERO);
	glEnable(GL_BLEND);

	quad = gluNewQuadric();
	gluQuadricNormals(quad, GL_SMOOTH);					// Generate Smooth Normals For The Quad
	gluQuadricTexture(quad, GL_TRUE);						// Enable Texture Coords For The Quad

	return TRUE;										// Initialization Went OK
}

int DrawGLScene(GLvoid)									// Here's Where We Do All The Drawing
{
	RECT	rect;														// Holds Coordinates Of A Rectangle

	GetClientRect(hWnd, &rect);								// Get Window Dimensions
	int window_width=rect.right-rect.left;					// Calculate The Width (Right Side-Left Side)
	int window_height=rect.bottom-rect.top;					// Calculate The Height (Bottom-Top)

	glClear(GL_COLOR_BUFFER_BIT);							// Clear The Screen And The Depth Buffer

	calcCoords();

	for (int loop = 0; loop < 2; loop++) {

		if (loop == 0) {
			glViewport (0, window_height / 4, window_width, window_height / 4 * 3);
			glMatrixMode (GL_PROJECTION);								// Select The Projection Matrix
			glLoadIdentity ();											// Reset The Projection Matrix
			// Set Up Perspective Mode To Fit 1/4 The Screen (Size Of A Viewport)
			gluPerspective( 45.0, (GLfloat)(width)/(GLfloat)(height), 0.1f, 100.0f );
		}

		if (loop == 1) {
			// Set The Viewport To The Top Left.  It Will Take Up Half The Screen Width And Height
			glViewport (0, 0, window_width, window_height / 4);
			glMatrixMode (GL_PROJECTION);								// Select The Projection Matrix
			glLoadIdentity ();											// Reset The Projection Matrix
			// Set Up Ortho Mode To Fit 1/4 The Screen (Size Of A Viewport)
			gluOrtho2D(0, window_width, window_height / 4, 0);
		}

		glMatrixMode (GL_MODELVIEW);									// Select The Modelview Matrix
		glLoadIdentity ();												// Reset The Modelview Matrix

		glClear (GL_DEPTH_BUFFER_BIT);									// Clear Depth Buffer


		if (loop == 0) {
			glTranslatef(0.0f, -0.5f, -6.0f);

			glColor4f(0.0f, 0.0f, 1.0f, L1);
			glPushMatrix();
			glTranslatef(x_1, y_1, z_1);
			gluSphere(quad, R1, 32, 16);
			glPopMatrix();

			glColor4f(1.0f, 0.0f, 0.0f, L2);
			glPushMatrix();
			glTranslatef(x_2, y_2, z_2);
			gluSphere(quad, R2, 32, 16);
			glPopMatrix();

			glColor3f(0.0f, 1.0f, 0.0f);
			glBegin(GL_LINES);
				glVertex3f(x_1, y_1, z_1);
				glVertex3f(x_2, y_2, z_2);
			glEnd();
		}

		if (loop == 1) {
			//int pos_x = (int)(theta);
			//int pos_y = (int)(I * window_height / 8 + window_height / 8);

			/*
			if ((int)(theta) % (1800 / scale) == 0) {
				float I = calcPhysics();
				graph[graph_inc] = I;
				graph_inc++;
				if (graph_inc >= scale)
					graph_inc = 0;
			}
			*/

			for (int i = 0; i < graph_inc; i++) {
				if (i >= 180) break;
				//if (graph[i + 1] == -1)
				//	break;
				glBegin(GL_LINES);
					glVertex2i((int)(i / (float)scale * window_width), window_height / 8 - (graph[i] - iMin) / pixelHeight);
					glVertex2i((int)((i + 1) / (float)scale * window_width), window_height / 8 - (graph[i + 1] - iMin) / pixelHeight);
				glEnd();
			}
			if ((int)theta % (1800 / scale) == 0)
				graph_inc++;
			if ((int)theta % 360 == 0)
				graph_inc = 0;
			//if ((int)theta % 1440 == 0)
			//	graph_inc = 0;

			//pixelHeight = (iMax - iMin) / window_height * 4;
		}
	}

	theta += 0.2f;

	glFlush();

	return TRUE;										// Everything Went OK
}

GLvoid KillGLWindow(GLvoid)								// Properly Kill The Window
{
	if (fullscreen)										// Are We In Fullscreen Mode?
	{
		ChangeDisplaySettings(NULL,0);					// If So Switch Back To The Desktop
		ShowCursor(TRUE);								// Show Mouse Pointer
	}

	if (hRC)											// Do We Have A Rendering Context?
	{
		if (!wglMakeCurrent(NULL,NULL))					// Are We Able To Release The DC And RC Contexts?
		{
			MessageBox(NULL,"Release Of DC And RC Failed.","SHUTDOWN ERROR",MB_OK | MB_ICONINFORMATION);
		}

		if (!wglDeleteContext(hRC))						// Are We Able To Delete The RC?
		{
			MessageBox(NULL,"Release Rendering Context Failed.","SHUTDOWN ERROR",MB_OK | MB_ICONINFORMATION);
		}
		hRC=NULL;										// Set RC To NULL
	}

	if (hDC && !ReleaseDC(hWnd,hDC))					// Are We Able To Release The DC
	{
		MessageBox(NULL,"Release Device Context Failed.","SHUTDOWN ERROR",MB_OK | MB_ICONINFORMATION);
		hDC=NULL;										// Set DC To NULL
	}

	if (hWnd && !DestroyWindow(hWnd))					// Are We Able To Destroy The Window?
	{
		MessageBox(NULL,"Could Not Release hWnd.","SHUTDOWN ERROR",MB_OK | MB_ICONINFORMATION);
		hWnd=NULL;										// Set hWnd To NULL
	}

	if (!UnregisterClass("OpenGL",hInstance))			// Are We Able To Unregister Class
	{
		MessageBox(NULL,"Could Not Unregister Class.","SHUTDOWN ERROR",MB_OK | MB_ICONINFORMATION);
		hInstance=NULL;									// Set hInstance To NULL
	}
}

/*	This Code Creates Our OpenGL Window.  Parameters Are:					*
 *	title			- Title To Appear At The Top Of The Window				*
 *	width			- Width Of The GL Window Or Fullscreen Mode				*
 *	height			- Height Of The GL Window Or Fullscreen Mode			*
 *	bits			- Number Of Bits To Use For Color (8/16/24/32)			*
 *	fullscreenflag	- Use Fullscreen Mode (TRUE) Or Windowed Mode (FALSE)	*/

BOOL CreateGLWindow(char* title, int width, int height, int bits, BOOL fullscreenflag)
{
	GLuint		PixelFormat;			// Holds The Results After Searching For A Match
	WNDCLASS	wc;						// Windows Class Structure
	DWORD		dwExStyle;				// Window Extended Style
	DWORD		dwStyle;				// Window Style
	RECT		WindowRect;				// Grabs Rectangle Upper Left / Lower Right Values
	WindowRect.left=(long)0;			// Set Left Value To 0
	WindowRect.right=(long)width;		// Set Right Value To Requested Width
	WindowRect.top=(long)0;				// Set Top Value To 0
	WindowRect.bottom=(long)height;		// Set Bottom Value To Requested Height

	fullscreen=fullscreenflag;			// Set The Global Fullscreen Flag

	hInstance			= GetModuleHandle(NULL);				// Grab An Instance For Our Window
	wc.style			= CS_HREDRAW | CS_VREDRAW | CS_OWNDC;	// Redraw On Size, And Own DC For Window.
	wc.lpfnWndProc		= (WNDPROC) WndProc;					// WndProc Handles Messages
	wc.cbClsExtra		= 0;									// No Extra Window Data
	wc.cbWndExtra		= 0;									// No Extra Window Data
	wc.hInstance		= hInstance;							// Set The Instance
	wc.hIcon			= LoadIcon(NULL, IDI_WINLOGO);			// Load The Default Icon
	wc.hCursor			= LoadCursor(NULL, IDC_ARROW);			// Load The Arrow Pointer
	wc.hbrBackground	= NULL;									// No Background Required For GL
	wc.lpszMenuName		= NULL;									// We Don't Want A Menu
	wc.lpszClassName	= "OpenGL";								// Set The Class Name

	if (!RegisterClass(&wc))									// Attempt To Register The Window Class
	{
		MessageBox(NULL,"Failed To Register The Window Class.","ERROR",MB_OK|MB_ICONEXCLAMATION);
		return FALSE;											// Return FALSE
	}

	if (fullscreen)												// Attempt Fullscreen Mode?
	{
		DEVMODE dmScreenSettings;								// Device Mode
		memset(&dmScreenSettings,0,sizeof(dmScreenSettings));	// Makes Sure Memory's Cleared
		dmScreenSettings.dmSize=sizeof(dmScreenSettings);		// Size Of The Devmode Structure
		dmScreenSettings.dmPelsWidth	= width;				// Selected Screen Width
		dmScreenSettings.dmPelsHeight	= height;				// Selected Screen Height
		dmScreenSettings.dmBitsPerPel	= bits;					// Selected Bits Per Pixel
		dmScreenSettings.dmFields=DM_BITSPERPEL|DM_PELSWIDTH|DM_PELSHEIGHT;

		// Try To Set Selected Mode And Get Results.  NOTE: CDS_FULLSCREEN Gets Rid Of Start Bar.
		if (ChangeDisplaySettings(&dmScreenSettings,CDS_FULLSCREEN)!=DISP_CHANGE_SUCCESSFUL)
		{
			// If The Mode Fails, Offer Two Options.  Quit Or Use Windowed Mode.
			if (MessageBox(NULL,"The Requested Fullscreen Mode Is Not Supported By\nYour Video Card. Use Windowed Mode Instead?","NeHe GL",MB_YESNO|MB_ICONEXCLAMATION)==IDYES)
			{
				fullscreen=FALSE;		// Windowed Mode Selected.  Fullscreen = FALSE
			}
			else
			{
				// Pop Up A Message Box Letting User Know The Program Is Closing.
				MessageBox(NULL,"Program Will Now Close.","ERROR",MB_OK|MB_ICONSTOP);
				return FALSE;									// Return FALSE
			}
		}
	}

	if (fullscreen)												// Are We Still In Fullscreen Mode?
	{
		dwExStyle=WS_EX_APPWINDOW;								// Window Extended Style
		dwStyle=WS_POPUP;										// Windows Style
		ShowCursor(FALSE);										// Hide Mouse Pointer
	}
	else
	{
		dwExStyle=WS_EX_APPWINDOW | WS_EX_WINDOWEDGE;			// Window Extended Style
		dwStyle=WS_OVERLAPPEDWINDOW;							// Windows Style
	}

	AdjustWindowRectEx(&WindowRect, dwStyle, FALSE, dwExStyle);		// Adjust Window To True Requested Size

	// Create The Window
	if (!(hWnd=CreateWindowEx(	dwExStyle,							// Extended Style For The Window
								"OpenGL",							// Class Name
								title,								// Window Title
								dwStyle |							// Defined Window Style
								WS_CLIPSIBLINGS |					// Required Window Style
								WS_CLIPCHILDREN,					// Required Window Style
								0, 0,								// Window Position
								WindowRect.right-WindowRect.left,	// Calculate Window Width
								WindowRect.bottom-WindowRect.top,	// Calculate Window Height
								NULL,								// No Parent Window
								NULL,								// No Menu
								hInstance,							// Instance
								NULL)))								// Dont Pass Anything To WM_CREATE
	{
		KillGLWindow();								// Reset The Display
		MessageBox(NULL,"Window Creation Error.","ERROR",MB_OK|MB_ICONEXCLAMATION);
		return FALSE;								// Return FALSE
	}

	static	PIXELFORMATDESCRIPTOR pfd=				// pfd Tells Windows How We Want Things To Be
	{
		sizeof(PIXELFORMATDESCRIPTOR),				// Size Of This Pixel Format Descriptor
		1,											// Version Number
		PFD_DRAW_TO_WINDOW |						// Format Must Support Window
		PFD_SUPPORT_OPENGL |						// Format Must Support OpenGL
		PFD_DOUBLEBUFFER,							// Must Support Double Buffering
		PFD_TYPE_RGBA,								// Request An RGBA Format
		0,  										// Select Our Color Depth
		0, 0, 0, 0, 0, 0,							// Color Bits Ignored
		0,											// No Alpha Buffer
		0,											// Shift Bit Ignored
		0,											// No Accumulation Buffer
		0, 0, 0, 0,									// Accumulation Bits Ignored
		16,											// 16Bit Z-Buffer (Depth Buffer)
		0,											// No Stencil Buffer
		0,											// No Auxiliary Buffer
		PFD_MAIN_PLANE,								// Main Drawing Layer
		0,											// Reserved
		0, 0, 0										// Layer Masks Ignored
    };
    pfd.cColorBits = bits;

    if (!(hDC=GetDC(hWnd)))                         // Did We Get A Device Context?
    {	KillGLWindow();								// Reset The Display
		MessageBox(NULL,"Can't Create A GL Device Context.","ERROR",MB_OK|MB_ICONEXCLAMATION);
		return FALSE;								// Return FALSE
	}

    if (!(PixelFormat=ChoosePixelFormat(hDC,&pfd)))// Did Windows Find A Matching Pixel Format?
	{
        PixelFormat=0;
		KillGLWindow();								// Reset The Display
		MessageBox(NULL,"Can't Find A Suitable PixelFormat.","ERROR",MB_OK|MB_ICONEXCLAMATION);
		return FALSE;								// Return FALSE
	}

	if(!SetPixelFormat(hDC,PixelFormat,&pfd))		// Are We Able To Set The Pixel Format?
	{
		KillGLWindow();								// Reset The Display
		MessageBox(NULL,"Can't Set The PixelFormat.","ERROR",MB_OK|MB_ICONEXCLAMATION);
		return FALSE;								// Return FALSE
	}

	if (!(hRC=wglCreateContext(hDC)))				// Are We Able To Get A Rendering Context?
	{
		KillGLWindow();								// Reset The Display
		MessageBox(NULL,"Can't Create A GL Rendering Context.","ERROR",MB_OK|MB_ICONEXCLAMATION);
		return FALSE;								// Return FALSE
	}

	if(!wglMakeCurrent(hDC,hRC))					// Try To Activate The Rendering Context
	{
		KillGLWindow();								// Reset The Display
		MessageBox(NULL,"Can't Activate The GL Rendering Context.","ERROR",MB_OK|MB_ICONEXCLAMATION);
		return FALSE;								// Return FALSE
	}

	ShowWindow(hWnd,SW_SHOW);						// Show The Window
	SetForegroundWindow(hWnd);						// Slightly Higher Priority
	SetFocus(hWnd);									// Sets Keyboard Focus To The Window
	//ReSizeGLScene(width, height);					// Set Up Our Perspective GL Screen

	if (!InitGL())									// Initialize Our Newly Created GL Window
	{
		KillGLWindow();								// Reset The Display
		MessageBox(NULL,"Initialization Failed.","ERROR",MB_OK|MB_ICONEXCLAMATION);
		return FALSE;								// Return FALSE
	}

	return TRUE;									// Success
}

LRESULT CALLBACK WndProc(	HWND	hWnd,			// Handle For This Window
							UINT	uMsg,			// Message For This Window
							WPARAM	wParam,			// Additional Message Information
							LPARAM	lParam)			// Additional Message Information
{
	switch (uMsg)									// Check For Windows Messages
	{
		case WM_ACTIVATE:							// Watch For Window Activate Message
		{
			if (!HIWORD(wParam))					// Check Minimization State
			{
				active=TRUE;						// Program Is Active
			}
			else
			{
				active=FALSE;						// Program Is No Longer Active
			}

			return 0;								// Return To The Message Loop
		}

		case WM_ERASEBKGND:
			return 0;

		case WM_SYSCOMMAND:							// Intercept System Commands
		{
			switch (wParam)							// Check System Calls
			{
				case SC_SCREENSAVE:					// Screensaver Trying To Start?
				case SC_MONITORPOWER:				// Monitor Trying To Enter Powersave?
				return 0;							// Prevent From Happening
			}
			break;									// Exit
		}

		case WM_KEYDOWN:							// Is A Key Being Held Down?
		{
			keys[wParam] = TRUE;					// If So, Mark It As TRUE
			return 0;								// Jump Back
		}

		case WM_KEYUP:								// Has A Key Been Released?
		{
			keys[wParam] = FALSE;					// If So, Mark It As FALSE
			return 0;								// Jump Back
		}

		case WM_CLOSE:								// Did We Receive A Close Message?
		{
			PostQuitMessage(0);						// Send A Quit Message
			return 0;								// Jump Back
		}

		case WM_SIZE:								// Resize The OpenGL Window
		{
			ReSizeGLScene(LOWORD(lParam),HIWORD(lParam));  // LoWord=Width, HiWord=Height
			return 0;								// Jump Back
		}
	}

	// Pass All Unhandled Messages To DefWindowProc
	return DefWindowProc(hWnd,uMsg,wParam,lParam);
}

int WINAPI WinMain(	HINSTANCE	hInstance,			// Instance
					HINSTANCE	hPrevInstance,		// Previous Instance
					LPSTR		lpCmdLine,			// Command Line Parameters
					int			nCmdShow)			// Window Show State
{
	MSG		msg;									// Windows Message Structure
	BOOL	done=FALSE;								// BOOL Variable To Exit Loop

	// Ask The User Which Screen Mode They Prefer
	/*
	if (MessageBox(NULL,"Would You Like To Run In Fullscreen Mode?", "Start FullScreen?",MB_YESNO|MB_ICONQUESTION)==IDNO)
	{
		fullscreen=FALSE;							// Windowed Mode
	}
	*/

	// Create Our OpenGL Window
	if (!CreateGLWindow("Simulace binárního systému", width, height, 32, fullscreen))
	{
		return 0;									// Quit If Window Was Not Created
	}

	while(!done)									// Loop That Runs While done=FALSE
	{
		if (PeekMessage(&msg,NULL,0,0,PM_REMOVE))	// Is There A Message Waiting?
		{
			if (msg.message==WM_QUIT)				// Have We Received A Quit Message?
			{
				done=TRUE;							// If So done=TRUE
			}
			else									// If Not, Deal With Window Messages
			{
				TranslateMessage(&msg);				// Translate The Message
				DispatchMessage(&msg);				// Dispatch The Message
			}
		}
		else										// If There Are No Messages
		{
			// Draw The Scene.  Watch For ESC Key And Quit Messages From DrawGLScene()
			if (active && !DrawGLScene())	// Active?  Was There A Quit Received?
			{
				done=TRUE;							// ESC or DrawGLScene Signalled A Quit
			}
			else									// Not Time To Quit, Update Screen
			{
				SwapBuffers(hDC);					// Swap Buffers (Double Buffering)

				if (keys[VK_F1])						// Is F1 Being Pressed?
				{
					keys[VK_F1]=FALSE;					// If So Make Key FALSE
					KillGLWindow();						// Kill Our Current Window
					fullscreen=!fullscreen;				// Toggle Fullscreen / Windowed Mode
					// Recreate Our OpenGL Window
					if (!CreateGLWindow("Simulace binárního systému", width, height, 32, fullscreen))
					{
						return 0;						// Quit If Window Was Not Created
					}
				}

				if (keys[VK_ESCAPE]) {
					PostQuitMessage(0);
				}
			}
		}
	}

	// Shutdown
	KillGLWindow();										// Kill The Window
	return (msg.wParam);								// Exit The Program
}

