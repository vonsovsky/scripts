#include "fce_3d.h"

char etext[490];
char etext2[490];
char autor[40] = "Grafy funkcí - Jakub Vonšovský - 2006";
char seznam[26][9] = { "tan", "cos", "log", "pow", "sqrt", "lgamma", "sin", "tgamma", "abs",
						"atan", "tanh", "ceil", "cbrt", "int", "acos", "asin", "cosh", "sinh",
					    "expl", "log10", "infinity", "asinh", "acosh", "atanh", "cotg", "trunc" };
char Espr[100] = "";
float theta = 2.0f;
float trans[3] = {-30.0f, 0.0f, -1.0f};
float x, y, z = 0.0f;
float speed = 0.1f;
float val;
float resolution = 5 * 1e-3f;
float inf = 0.0f;
int resx = 0;
int resy = 0;
float zasobnik[3][2] = 0.0f;
float alphac = 0.3f;
BOOL thin = FALSE;
BOOL blackbgr = FALSE;
BOOL zapis = TRUE;
BOOL uprava = FALSE;
BOOL play = FALSE;
int  grid = TRUE;
BOOL yorz = 1;
BOOL	keys[256];
BOOL	active=TRUE;
BOOL	fullscreen=TRUE;
BOOL	first=TRUE;
HDC			hDC=NULL;
HGLRC		hRC=NULL;
HWND		hWnd=NULL;
HWND        Hwnd=NULL;
HWND		hwnD=NULL;
HWND        hEdit;
HWND		hList;
HINSTANCE	hInstance;
FILE		*fhin;

GLuint	base;

LRESULT	CALLBACK WndProc(HWND, UINT, WPARAM, LPARAM);

GLvoid BuildFont(GLvoid)
{
    GLYPHMETRICSFLOAT gmf[256];
	HFONT	font;

	base = glGenLists(256);

	font = CreateFont(	-12,
						0,
						0,
						0,
						FW_THIN,
						FALSE,
						FALSE,
						FALSE,
						ANSI_CHARSET,
						OUT_TT_PRECIS,
						CLIP_DEFAULT_PRECIS,
						ANTIALIASED_QUALITY,
						FF_DONTCARE|DEFAULT_PITCH,
						"Courier New");

	SelectObject(hDC, font);

	wglUseFontOutlines(	hDC,
						32,
						255,
						base,
						0.0f,
						0.005f,
						WGL_FONT_POLYGONS,
						gmf);
}

GLvoid KillFont(GLvoid)
{
	glDeleteLists(base, 96);
}

GLvoid glPrint(const char *fmt, ...)
{
	char		text[256];
	va_list		ap;

	if (fmt == NULL)
		return;

	va_start(ap, fmt);
	    vsprintf(text, fmt, ap);
	va_end(ap);

	glPushAttrib(GL_LIST_BIT);
	glListBase(base - 32);
	glCallLists(strlen(text), GL_UNSIGNED_BYTE, text);
	glPopAttrib();
}

GLvoid ReSizeGLScene(GLsizei width, GLsizei height)
{
	if (height == 0) height = 1;
	glViewport(0,0,width,height);
	glMatrixMode(GL_PROJECTION);
	glLoadIdentity();
	gluPerspective(45.0f,(GLfloat)width/(GLfloat)height,0.1f,100.0f);
	glMatrixMode(GL_MODELVIEW);
	glLoadIdentity();
}

int InitGL(GLvoid)
{
	glShadeModel(GL_SMOOTH);
	glClearColor(0.2f, 0.5f, 1.0f, 1.0f);
	glClearDepth(1.0f);
	glHint(GL_PERSPECTIVE_CORRECTION_HINT, GL_NICEST);
	if (blackbgr == FALSE) glBlendFunc(GL_SRC_ALPHA,GL_ONE_MINUS_SRC_ALPHA);
	else glBlendFunc(GL_SRC_ALPHA,GL_ONE);
	glEnable(GL_BLEND);
	glEnable(GL_DEPTH_TEST);
	glDepthFunc(GL_LEQUAL);
	BuildFont();

	return TRUE;
}

int DrawGLScene(GLvoid)
{
    if (GetFocus() != hWnd) return TRUE;
	int i = 0;
    float j = 0;
    //float alpha = 0.1f;

    if (blackbgr == FALSE) glClearColor (1.0f, 1.0f, 1.0f, 0.5f);
	else glClearColor (0.0f, 0.0f, 0.0f, 0.5f);
    glClear (GL_COLOR_BUFFER_BIT | GL_DEPTH_BUFFER_BIT);

    glPushMatrix ();
    glTranslatef(trans[1], trans[2], trans[0]);
    glRotatef(theta, 0.0f, 1.0f, 0.0f);

    glBegin (GL_POINTS);
	if (CheckErrors() == 1) return TRUE;
	ExtractStrings();
	float rozdil = 0.0f;
	zasobnik[2][0] = -10.0f; x = -10.0f;
	zasobnik[0][0] = Convert(2, strlen(etext)-1, x);
	zasobnik[1][0] = Convert(2, strlen(etext2)-1, x);
    for (x = -10.0f - inf; x <= 10.0f + inf; x += resolution) {
        /*zasobnik[2][1] = zasobnik[2][0];
		zasobnik[2][0] = x;
		yorz = 1;
		y = Convert(2, strlen(etext)-1, x);
		zasobnik[0][1] = zasobnik[0][0];
		zasobnik[0][0] = y;
		yorz = 2;
        z = Convert(2, strlen(etext2)-1, x);
		zasobnik[1][1] = zasobnik[1][0];
		zasobnik[1][0] = z;
        glColor4f (1.0f, 0.0f, 0.0f, 1.0f);*/
		/*if (x == 10.0f) {
			MessageBeep(0);
		}*/
		/*if ((x <= 1.001f) && (x >= 1.000f)) {
			break;
		}*/
		/*	glEnd();
			glBegin(GL_QUADS);
			glVertex3f(-0.5f, -0.5f, 0.0f);
			glVertex3f(0.5f, -0.5f, 0.0f);
			glVertex3f(0.5f, 0.5f, 0.0f);
			glVertex3f(-0.5f, 0.5f, 0.0f);
			glEnd();
			glBegin(GL_LINES);
		}
		if ((zasobnik[0][0] == zasobnik[0][1]) && (zasobnik[1][0] == zasobnik[1][1])) {
			zasobnik[0][0] += 0.01f;
			zasobnik[1][0] += 0.01f;
		}*/
		/*rozdil = zasobnik[0][0] - zasobnik[0][1];
		if (rozdil == 0.0f) {
		//if ((rozdil > -10e-4f) && (rozdil < 10e-4f)) {
			glEnd();
			glBegin(GL_POINTS);
			glVertex3f (x, zasobnik[0][0], zasobnik[1][0]);
			glEnd();
			glBegin(GL_LINES);
			continue;
		}
		//if (zasobnik[0][0] < 10e-4f) && (zasobnik[0][0] > 0.0f) && (zasobnik[0][1] <
		rozdil = zasobnik[1][0] - zasobnik[1][1];
		if (rozdil == 0.0f) {
		//if ((rozdil > -10e-4f) && (rozdil < 10e-4f)) {
			glEnd();
			glBegin(GL_POINTS);
			glVertex3f (x, zasobnik[0][0], zasobnik[1][0]);
			glEnd();
			glBegin(GL_LINES);
			continue;
		}*/
		//glVertex3f (zasobnik[2][1], zasobnik[0][1], zasobnik[1][1]);
		//glVertex3f (zasobnik[2][0], zasobnik[0][0], zasobnik[1][0]);
		yorz = 1;
		y = Convert(2, strlen(etext)-1, x);
		yorz = 2;
        z = Convert(2, strlen(etext2)-1, x);
        glColor4f (1.0f, 0.0f, 0.0f, 1.0f);
		glVertex3f(x, y, z);
		/*if (thin == FALSE) {
			glVertex3f(x+resolution, y, z);
			glVertex3f(x, y, z+resolution);
			glVertex3f(x+resolution, y, z+resolution);
			glVertex3f(x, y+resolution, z);
			glVertex3f(x+resolution, y+resolution, z);
			glVertex3f(x, y+resolution, z+resolution);
			glVertex3f(x+resolution, y+resolution, z+resolution);
		}*/
    }
    glEnd ();
	//MessageBox(NULL, "hotovo", "", 0 + MB_OK + MB_TASKMODAL);
    if (grid > 0) {
        glPushMatrix();
        int maxi = (grid == 2)? 1:0;
		for (i = 0; i <= maxi; i++) {
            int ah = 0;
			glRotatef(i * 90.0f, 0.0f, 1.0f, 0.0f);
            glBegin (GL_LINES);
            //hor
            for (j = -10.0f; j <= 10.0f; j += 1.0f) {
               //if (j == 0.0f) alphac += 0.1f;
               //else alpha -= 0.1f;
               if (blackbgr == FALSE) glColor4f (0.0f, 0.0f, 0.0f, alphac);
		       else glColor4f (0.0f, 1.0f, 0.0f, alphac);
		       glVertex3f (-10.0f, j, 0.0f);
               glVertex3f ( 10.0f, j, 0.0f);
            }
            //ver
            for (j = -10.0f; j <= 10.0f; j += 1.0f) {
                //if (j == 0.0f) alpha = 0.2f;
                //else alpha = 0.1f;
                glVertex3f ( j, -10.0f, 0.0f);
                glVertex3f ( j,  10.0f, 0.0f);
            }
            glEnd();
        }
        glPopMatrix();

            //hor
            int k = 0;
			alphac -= 0.1f;
			glColor4f(0.0f, 0.0f, 0.0f, alphac);
            for (k = 10; k >= -10; k--) {
                glPushMatrix();
                if (k < 0) glTranslatef(-0.8f, k - 0.3f, 0.0f);
                else glTranslatef(-0.3f, k - 0.3f, 0.0f);
                glPrint("%d", k);
                glPopMatrix();
            }
            //ver
            for (k = -10; k <= +10; k++) {
                glPushMatrix();
                if (k != -10) glTranslatef(k - 0.3f, -0.3f, 0.0f);
                else glTranslatef(k - 0.6f, -0.3f, 0.0f);
                if (k < 0) glPrint("%d", -k);
                else glPrint("%d", k);
                glPopMatrix();
            }
			alphac += 0.1;
    }
    glPopMatrix ();

	return TRUE;
}

GLvoid KillGLWindow(GLvoid)
{
	DestroyWindow(Hwnd);
	DestroyWindow(hwnD);
	if (fullscreen)
	{
		ChangeDisplaySettings(NULL,0);
		ShowCursor(TRUE);
	}

	if (hRC)
	{
		if (!wglMakeCurrent(NULL,NULL))
		{
			MessageBox(NULL,"Chyba pøi pouštìní DC a RC","chyba",MB_OK | MB_ICONINFORMATION);
		}

		if (!wglDeleteContext(hRC))
		{
			MessageBox(NULL,"Pouštìní renderingu selhalo","chyba",MB_OK | MB_ICONINFORMATION);
		}
		hRC=NULL;
	}

	if (hDC && !ReleaseDC(hWnd,hDC))
	{
		MessageBox(NULL,"pouštìní kontextu zaøízení selhalo","chyba",MB_OK | MB_ICONINFORMATION);
		hDC=NULL;
	}

	if (hWnd && !DestroyWindow(hWnd))
	{
		MessageBox(NULL,"nemohu pustit instanci","chyba",MB_OK | MB_ICONINFORMATION);
		hWnd=NULL;
	}

	if (!UnregisterClass("OpenGL",hInstance))
	{
		MessageBox(NULL,"Nemohu odregistrovat okno","chyba",MB_OK | MB_ICONINFORMATION);
		hInstance=NULL;
	}
	KillFont();
}

BOOL CreateGLWindow(char* title, int width, int height, int bits, BOOL fullscreenflag)
{
	GLuint		PixelFormat;
	WNDCLASS	wc;
	DWORD		dwExStyle;
	DWORD		dwStyle;
	RECT		WindowRect;
	WindowRect.left=(long)0;
	WindowRect.right=(long)width;
	WindowRect.top=(long)0;
	WindowRect.bottom=(long)height;

	fullscreen=fullscreenflag;

	hInstance			= GetModuleHandle(NULL);
	wc.style			= CS_HREDRAW | CS_VREDRAW | CS_OWNDC | CS_DBLCLKS;
	wc.lpfnWndProc		= (WNDPROC) WndProc;
	wc.cbClsExtra		= 0;
	wc.cbWndExtra		= 0;
	wc.hInstance		= hInstance;
	wc.hIcon			= LoadIcon(hInstance, MAKEINTRESOURCE(ID_ICON));
	wc.hCursor			= LoadCursor(NULL, IDC_ARROW);
	wc.hbrBackground	= NULL;
	wc.lpszMenuName		= NULL;
	wc.lpszClassName	= "OpenGL";

	if (!RegisterClass(&wc))
	{
		MessageBox(NULL,"chyba pøi registru okna","chyba",MB_OK|MB_ICONEXCLAMATION);
		return FALSE;
	}

	if (fullscreen)
	{
		DEVMODE dmScreenSettings;
		memset(&dmScreenSettings,0,sizeof(dmScreenSettings));
		dmScreenSettings.dmSize=sizeof(dmScreenSettings);
		dmScreenSettings.dmPelsWidth	= width;
		dmScreenSettings.dmPelsHeight	= height;
		dmScreenSettings.dmBitsPerPel	= bits;
		dmScreenSettings.dmFields=DM_BITSPERPEL|DM_PELSWIDTH|DM_PELSHEIGHT;

		if (ChangeDisplaySettings(&dmScreenSettings,CDS_FULLSCREEN)!=DISP_CHANGE_SUCCESSFUL)
		{
			if (MessageBox(NULL,"požadovaný fullscreen mód není podporován\nchcete radìji v oknì?","chyba videokarty",MB_YESNO|MB_ICONEXCLAMATION)==IDYES)
			{
				fullscreen=FALSE;
			}
			else
			{
				MessageBox(NULL,"Program se nyní zavøe","chyba",MB_OK|MB_ICONSTOP);
				return FALSE;
			}
		}
	}

	if (fullscreen)
	{
		dwExStyle=WS_EX_APPWINDOW;
		dwStyle=WS_POPUP;
		ShowCursor(FALSE);
	}
	else
	{
		dwExStyle=WS_EX_APPWINDOW | WS_EX_WINDOWEDGE;
		dwStyle=WS_OVERLAPPEDWINDOW;
	}

	AdjustWindowRectEx(&WindowRect, dwStyle, FALSE, dwExStyle);

	if (!(hWnd=CreateWindowEx(	dwExStyle,
								"OpenGL",
								title,
								WS_CLIPSIBLINGS | WS_CLIPCHILDREN | dwStyle/* | WS_SYSMENU | WS_MINIMIZEBOX*/,
								0, 0,
								WindowRect.right-WindowRect.left,
								WindowRect.bottom-WindowRect.top,
								NULL,
								NULL,
								hInstance,
								NULL)))
	{
		KillGLWindow();
		MessageBox(NULL,"chyba vytváøení okna","chyba",MB_OK|MB_ICONEXCLAMATION);
		return FALSE;
	}

    Hwnd=CreateWindowEx(0, "OpenGL", "Definuj funkce y, z", WS_CLIPSIBLINGS | WS_CLIPCHILDREN | WS_VISIBLE | WS_SYSMENU,
								WindowRect.right-WindowRect.left, 0, 190, 120,
								HWND_DESKTOP, NULL, hInstance, NULL);


    hEdit=CreateWindowEx(WS_EX_CLIENTEDGE, "edit", "", WS_VISIBLE | ES_AUTOHSCROLL | ES_MULTILINE | WS_CHILD | ES_WANTRETURN | WS_HSCROLL | ES_LOWERCASE,
								0, 0, 185, 95,
								Hwnd, (HMENU)ID_EDIT, hInstance, NULL);

    hwnD=CreateWindowEx(0, "OpenGL", "", WS_CLIPSIBLINGS | WS_CLIPCHILDREN | WS_VISIBLE | WS_SYSMENU,
								WindowRect.right-WindowRect.left, 120, 190, WindowRect.bottom-WindowRect.top - 120,
								HWND_DESKTOP, NULL, hInstance, NULL);

    hList=CreateWindowEx(WS_EX_CLIENTEDGE, "listbox", "", WS_VISIBLE | WS_CHILD | WS_TABSTOP | WS_VSCROLL | LBS_NOTIFY | LBS_WANTKEYBOARDINPUT,
								0, 0, 190, WindowRect.bottom-WindowRect.top,
								hwnD, (HMENU)ID_LIST, hInstance, NULL);

    static	PIXELFORMATDESCRIPTOR pfd=
	{
		sizeof(PIXELFORMATDESCRIPTOR),
		1,
		PFD_DRAW_TO_WINDOW |
		PFD_SUPPORT_OPENGL |
		PFD_DOUBLEBUFFER,
		PFD_TYPE_RGBA,
		32,
		0, 0, 0, 0, 0, 0,
		0,
		0,
		0,
		0, 0, 0, 0,
		16,
		0,
		0,
		PFD_MAIN_PLANE,
		0,
		0, 0, 0
	};

	if (!(hDC=GetDC(hWnd)))
	{
		KillGLWindow();
		MessageBox(NULL,"Nemohu vytvoøit kontext zaøízení","chyba",MB_OK|MB_ICONEXCLAMATION);
		return FALSE;
	}

	if (!(PixelFormat=ChoosePixelFormat(hDC,&pfd)))
	{
		KillGLWindow();
		MessageBox(NULL,"Nemohu najít odpovídající PixelFormát","chyba",MB_OK|MB_ICONEXCLAMATION);
		return FALSE;
	}

	if(!SetPixelFormat(hDC,PixelFormat,&pfd))
	{
		KillGLWindow();
		MessageBox(NULL,"Nemohu nastavit PixelFormát","chyba",MB_OK|MB_ICONEXCLAMATION);
		return FALSE;
	}

	if (!(hRC=wglCreateContext(hDC)))
	{
		KillGLWindow();
		MessageBox(NULL,"Nemohu vytvoøit kontext renderingu","chyba",MB_OK|MB_ICONEXCLAMATION);
		return FALSE;
	}

	if(!wglMakeCurrent(hDC,hRC))
	{
		KillGLWindow();
		MessageBox(NULL,"Nemohu vytvoøit GL kontext renderingu","chyba",MB_OK|MB_ICONEXCLAMATION);
		return FALSE;
	}

	ShowWindow(hWnd,SW_SHOW);
	SetForegroundWindow(hWnd);
	SetFocus(hWnd);
	ReSizeGLScene(width, height);
	SetTimer(hWnd, 1, 1, NULL);
	SendMessage(hWnd, WM_TIMER, 1, 0);
	SendMessage(hEdit, EM_LIMITTEXT, 990, 0);
	SetWindowText(hwnD, "Seznam nadefinovaných funkcí");
	if ((fhin = fopen("definitions.txt", "a+")) == NULL) {
		zapis = FALSE;
		MessageBox(hWnd, "Do souboru s funkcemi nelze ukládat\nMožná je využíván jiným procesem nebo je disk zaplnìn", "Chyba v zápisu", 0 + MB_OK + MB_ICONWARNING);
	}
	NaplnList();
	if (ControlExeChange() == 1) {
		MessageBox(hWnd, "Terminace v dùsledku nepovolené zmìny v exe souboru", "Chyba", 0 + MB_OK + MB_TASKMODAL + MB_ICONERROR);
		PostQuitMessage(0);
	}


	if (!InitGL())
	{
		KillGLWindow();
		MessageBox(NULL,"Inicializace se nezdaøila","chyba",MB_OK|MB_ICONEXCLAMATION);
		return FALSE;
	}

	return TRUE;
}

LRESULT CALLBACK WndProc(	HWND	hWnd,
							UINT	uMsg,
							WPARAM	wParam,
							LPARAM	lParam)
{
    switch (uMsg)
	{
		case WM_COMMAND:
			switch(LOWORD(wParam)) {
				case ID_LIST:
					if (HIWORD(wParam) == LBN_DBLCLK) {
						PrepisPredpis();
					}
					break;
			}
			break;

		case WM_ACTIVATE:
		{
			if (!HIWORD(wParam))
			{
				active=TRUE;
			}
			else
			{
				active=FALSE;
			}

			return 0;
		}

		case WM_SYSCOMMAND:
		{
			switch (wParam)
			{
                case SC_MINIMIZE:
					CloseWindow(hwnD);
					CloseWindow(Hwnd);
                    break;
				case SC_RESTORE:
					ShowWindow(hwnD, SW_RESTORE);
					ShowWindow(Hwnd, SW_RESTORE);
					break;
				case SC_SCREENSAVE:
				case SC_MONITORPOWER:
				return 0;
			}
			break;
		}

    case WM_CREATE:
        return 0;
    case WM_CLOSE:
        if (zapis == TRUE) {
			//uzavreni souboru predpisu
			fclose(fhin);
			//ulozeni aktivnich vzorcu + fullscreen nastaveni
			SaveActive();
			SendMessage(hEdit, WM_SETTEXT, 20, (LPARAM)"");
		}
		PostQuitMessage (0);
        return 0;

    case WM_DESTROY:
        return 0;

    case WM_TIMER:
         if ((play == TRUE) && (GetFocus() == hWnd)) theta += speed;
         break;

    case WM_LBUTTONDBLCLK:
        break;

	case WM_LBUTTONDOWN:
		break;

	case WM_SETFOCUS:
		break;

    case WM_KEYUP:
         switch (wParam) {
           case VK_SPACE:
                play = !play;
                break;
           case 'G':
                grid--;
				if (grid == -1) grid = 2;
                break;
           case VK_F1:
				break;

         }
         break;
    case WM_KEYDOWN:
        switch (wParam)
        {
        case VK_ESCAPE:
            fclose(fhin);
			PostQuitMessage(0);
            return 0;
        case VK_DOWN:
             if (GetFocus() != hwnD) trans[0] -= 1.0f;
			 if (GetFocus() == hwnD) {
				 int cnt = SendMessage(hList, LB_GETCOUNT, 0, 0);
				 int set = SendMessage(hList, LB_GETCURSEL, 0, 0);
				 if (set != cnt - 1) SendMessage(hList, LB_SETCURSEL, set + 1, 0);
			 }
             break;
        case VK_UP:
             if (GetFocus() != hwnD) trans[0] += 1.0f;
			 if (GetFocus() == hwnD) {
				 int set = SendMessage(hList, LB_GETCURSEL, 0, 0);
				 if (set != 0) SendMessage(hList, LB_SETCURSEL, set - 1, 0);
			 }
             break;
        case VK_RIGHT:
             if (GetFocus() != hwnD) trans[1] -= 1.0f;
			 if (GetFocus() == hwnD) {
				 int cnt = SendMessage(hList, LB_GETCOUNT, 0, 0);
				 int set = SendMessage(hList, LB_GETCURSEL, 0, 0);
				 if (set != cnt - 1) SendMessage(hList, LB_SETCURSEL, set + 1, 0);
			 }
             break;
        case VK_LEFT:
             if (GetFocus() != hwnD) trans[1] += 1.0f;
			 if (GetFocus() == hwnD) {
				 int set = SendMessage(hList, LB_GETCURSEL, 0, 0);
				 if (set != 0) SendMessage(hList, LB_SETCURSEL, set - 1, 0);
			 }
             break;
        case VK_PRIOR:
             trans[2] -= 1.0f;
             break;
        case VK_NEXT:
             trans[2] += 1.0f;
             break;
        case VK_ADD:
             speed += 0.1f;
			 //speed += 2.0f;
             break;
        case VK_SUBTRACT:
             speed -= 0.1f;
             break;
        case 'A':
			alphac += 0.1f;
			if (alphac > 1.0f) alphac = 0.0f;
			break;
		case 'I':
			 if (inf == 0.0f) inf = 50.0f;
			 else inf = 0.0f;
			 break;
		case 'C':
             play = FALSE;
             theta = 2.0f;
             trans[0] = -30.0f;
             trans[1] = 0.0f;
             trans[2] = -1.0f;
             speed = 0.1f;
			 inf = 0.0f;
			 resolution = 5*1e-3f;
             break;
		case VK_INSERT:
			resolution /= 2.0f;
			break;
		case VK_DELETE:
			if (GetFocus() != hwnD) resolution *= 2.0f;
			if (GetFocus() == hwnD) {
				if (zapis == TRUE) {
					if (MessageBox(hwnD, "Pøejete si editovat seznam pøedpisù ruènì\nProgram se tímto zavøe, pøejete si pokraèovat?", "Editace seznamu", 0 + MB_OKCANCEL + MB_ICONQUESTION + MB_TASKMODAL) == IDOK) {
						fclose(fhin);
						ShellExecute(NULL, "open", "definitions.txt", NULL, NULL, SW_SHOWNORMAL);
						PostQuitMessage(0);
					}
				} else MessageBox(hWnd, "Soubor s definicemi nenalezen!", "Chyba", 0 + MB_OK + MB_ICONERROR + MB_TASKMODAL);
			}
			break;
		case VK_SPACE:
			if (GetFocus() == hwnD) {
				if (zapis == TRUE) {
					if (MessageBox(hwnD, "Pøejete si editovat seznam pøedpisù ruènì\nProgram se tímto zavøe, pøejete si pokraèovat?", "Editace seznamu", 0 + MB_OKCANCEL + MB_ICONQUESTION + MB_TASKMODAL) == IDOK) {
						fclose(fhin);
						ShellExecute(NULL, "open", "definitions.txt", NULL, NULL, SW_SHOWNORMAL);
						PostQuitMessage(0);
					}
				} else MessageBox(hWnd, "Soubor s definicemi nenalezen!", "Chyba", 0 + MB_OK + MB_ICONERROR + MB_TASKMODAL);
			}
			break;
		case VK_F1:
			char mText[800];
			strcpy(mText, "--Program vykreslující funkce--\n\n");
			strcat(mText, "Pohyb grafu do stran - šipky, pgup, pgdn\n");
			strcat(mText, "Rotující graf - mezerník\n");
			strcat(mText, "Zvýšení rychlosti otáèení -  plus, minus\n");
			strcat(mText, "Zmìna rozlišení zobrazení - insert, delete\n");
			strcat(mText, "Zapnutí, vypnutí møížky - g\n");
			strcat(mText, "Zaèáteèní nastavení grafu - c\n");
			strcat(mText, "Zvýšení rozsahu grafu - i\n");
			strcat(mText, "Editace seznamu - v seznamu zadat mezerník\n\n");
			strcat(mText, "Vzorce pokud možno zadávat bez mezer\n");
			strcat(mText, "Každý vzorec musí být zakonèen støedníkem\n");
			strcat(mText, "Pøednost násobení a dìlení není brána v úvahu\n\n");
			strcat(mText, "Dìkuji za používání tohoto programu\n");
			strcat(mText, autor);
			MessageBox(hWnd,
					  mText,
					  "Pomoc",
					  0 + MB_OK + MB_ICONASTERISK);
			break;
		case VK_F2:
			blackbgr = !blackbgr;
			if (blackbgr == FALSE) glBlendFunc(GL_SRC_ALPHA,GL_ONE_MINUS_SRC_ALPHA);
			else glBlendFunc(GL_SRC_ALPHA,GL_ONE);
			break;
		case VK_F3:
			thin = !thin;
			break;
        case VK_RETURN:
	       	if (GetFocus() != hwnD) {
				if (MessageBox(hWnd, "Pøejete si pøidat aktuální vzorce do seznamu?", "Seznam", 0 + MB_OKCANCEL + MB_ICONQUESTION) == IDOK) {
					ExtractStrings();
					char fromfile[3];
					if (zapis == TRUE) if (fgets(fromfile, 2, fhin) != NULL) first = 0;
					if (zapis == TRUE) {
						if (strcmp(etext, "y=0;") != 0) {
							SendMessage(hList, LB_ADDSTRING, 0, (LPARAM)etext);
							if (first == 1) fputs("\n", fhin);
							fputs(etext, fhin);
							first = 0;
						}
						if (strcmp(etext2, "z=0;") != 0) {
							SendMessage(hList, LB_ADDSTRING, 0, (LPARAM)etext2);
							if (first == 1) fputs("\n", fhin);
							fputs(etext2, fhin);
							first = 0;
						}
					}
				}
			}
			if (GetFocus() == hwnD) {
				PrepisPredpis();
			}
			break;
        }
        break;

    case WM_VKEYTOITEM:
		if ((LOWORD(wParam) == VK_SPACE) || (LOWORD(wParam) == VK_DELETE)) {
			if (zapis == TRUE) {
				if (MessageBox(hwnD, "Pøejete si editovat seznam pøedpisù ruènì\nProgram se tímto zavøe, pøejete si pokraèovat?", "Editace seznamu", 0 + MB_OKCANCEL + MB_ICONQUESTION + MB_TASKMODAL) == IDOK) {
					fclose(fhin);
					ShellExecute(NULL, "open", "definitions.txt", NULL, NULL, SW_SHOWNORMAL);
					PostQuitMessage(0);
				}
			} else MessageBox(hWnd, "Soubor s definicemi nenalezen!", "Chyba", 0 + MB_OK + MB_ICONERROR + MB_TASKMODAL);
		}
		if (LOWORD(wParam) == VK_RETURN) PrepisPredpis();
		break;

	case WM_SIZE:
		{
			ReSizeGLScene(LOWORD(lParam),HIWORD(lParam));
			return 0;
		}
	}

	return DefWindowProc(hWnd,uMsg,wParam,lParam);
}

int WINAPI WinMain(	HINSTANCE	hInstance,
					HINSTANCE	hPrevInstance,
					LPSTR		lpCmdLine,
					int			nCmdShow)
{
	MSG		msg;
	BOOL	done=FALSE;

	//if (MessageBox(NULL,"Pøejete si fullscreen?", "FullScreen",MB_YESNO|MB_ICONQUESTION)==IDNO)
	//{
		fullscreen=FALSE;
	//}

	resx = GetSystemMetrics(SM_CXSCREEN);
	resy = GetSystemMetrics(SM_CYSCREEN);
	if (!CreateGLWindow("Grafy Funkcí",resx-200,resy-150,32,fullscreen))
	{
		return 0;
	}
	LoadActive();
	SendMessage(hEdit, WM_SETTEXT, 20, (LPARAM)Espr);
	//SetFocus(Hwnd);

	while(!done)
	{
		if (PeekMessage(&msg,NULL,0,0,PM_REMOVE))
		{
			if (msg.message==WM_QUIT)
			{
				done=TRUE;
			}
			else
			{
				TranslateMessage(&msg);
				DispatchMessage(&msg);
			}
		}
		else
		{
			if ((active && !DrawGLScene()) || keys[VK_ESCAPE])
			{
				done=TRUE;
			}
			else
			{
				SwapBuffers(hDC);
			}
		}
	}

	KillGLWindow();
	return (msg.wParam);
}

float Convert(int start, int finish, float value) {
	int matrix[10][3];
	int rozsah;
	int rozsah1;
	char string[1000];
	if (yorz == 1) strcpy(string, etext);
	if (yorz == 2) strcpy(string, etext2);
	if (string[start] != 40) matrix[0][1] = start - 2;
	else matrix[0][1] = start - 2;
	char extto[400] = "";
	int l = start;
	int zavky = 0;
	int hranice = 0;
	//if (string[start] != 40) hranice = 0;
	//else hranice = 1;
	int ainc = 0;
	for (int n = start; n <= finish; n++) {
		if (string[n] == 40) zavky++;
		/*if ((string[n] == 47) || (string[n] == 42) || (string[n] == 43) || (string[n] == 45) || (string[n] == 59) || (string[n] == 41)) {
			if (zavky == hranice) {
				ainc++;
				matrix[ainc][0] = matrix[ainc-1][1] + 2;
				matrix[ainc][1] = n - 1;
				matrix[ainc][2] = string[n];
			}
		}*/
		//if (string[n] == 41) zavky--;
		if ((string[n] == 47) || (string[n] == 42) || (string[n] == 43) || (string[n] == 45)) {
			//if (n != start) {
				if (zavky == hranice) {
					ainc++;
					matrix[ainc][0] = matrix[ainc-1][1] + 2;
					matrix[ainc][1] = n - 1;
					matrix[ainc][2] = string[n];
				}
			//}
		}
		if (string[n] == 41) zavky--;
		if ((n == finish) && (ainc > 0)) {
			if (zavky == hranice) {
				ainc++;
				matrix[ainc][0] = matrix[ainc-1][1] + 2;
				if (string[n] != 59) matrix[ainc][1] = n;
				else matrix[ainc][1] = n - 1;
				matrix[ainc][2] = string[n];
			}
		}
		//if (string[n] == 41) zavky--;
	}
	if (ainc > 1) {
		float adds = 0;
		adds = Convert(matrix[1][0], matrix[1][1], value);
		for (int o = 2; o <= ainc; o++) {
			switch(matrix[o-1][2]) {
				case 42:
					adds *= Convert(matrix[o][0], matrix[o][1], value);
					break;
				case 43:
					adds += Convert(matrix[o][0], matrix[o][1], value);
					break;
				case 45:
					adds -= Convert(matrix[o][0], matrix[o][1], value);
					break;
				case 47:
					adds /= Convert(matrix[o][0], matrix[o][1], value);
					break;
			}
		}
		val = adds;
		return val;
	} else {
		int correction = 0;
		int propust = 0;
		for (int p = start; p <= finish; p++) {
			if ((string[p] == 40) && (p != start)) {
				propust++;
				/*if (string[p-1] != 40) {
					if ((string[p-1] != 42) && (string[p-1] != 43) && (string[p-1] != 45) && (string[p-1] != 47))
						break;
					else propust++;
				} else propust++;*/
			}
			if ((string[p] == 40) && (p == start)) {
				correction = 1;
				propust++;
				continue;
			}
			/*if (l == finish) {
				l++;
				break;
			}*/
			if ((string[p] == 41) || (string[p] == 59)/* || (string[p] == 44)*/) {
				propust--;
				if (propust == 0) {
					l++;
					break;
				}
			}
			if (string[p] != 59) extto[p - start - correction] = string[p];
			l++;
		}
		/*while((string[l] != 59) && (string[l] != 40) && (string[l] != 41) && (l != finish)) {
			extto[l - start] = string[l];
			l++;
		}*/
	}
	if (string[start] == 40) {
		val = Convert(start + 1, start + strlen(extto), value);
		return val;
	}
	char tocpy[500] = "";
	int ac = start;

	while (string[ac] != 40) {
		tocpy[ac-start] = string[ac];
		ac++;
		if (ac == strlen(string)) break;
	}
	for (int m = 0; m <= 25; m++) {
		if (strcmp(seznam[m], tocpy) == 0) {
			switch(m) {
				case 0:
					rozsah = Nastav(string, start+strlen(tocpy), 41);
					val = (float)tan(Convert(start+strlen(tocpy)+1, rozsah, value));
					break;
				case 1:
					rozsah = Nastav(string, start+strlen(tocpy), 41);
					val = (float)cos(Convert(start+strlen(tocpy)+1, rozsah, value));
					break;
				case 2:
					rozsah = Nastav(string, start+strlen(tocpy), 41);
					val = (float)log(Convert(start+strlen(tocpy)+1, rozsah, value));
					break;
				case 3:
					rozsah1 = Nastav(string, start+strlen(tocpy), 44);
					rozsah = Nastav(string, start+strlen(tocpy), 41);
					val = (Convert(rozsah1+2, rozsah, value) > 0)? (float)pow(Convert(start+strlen(tocpy)+1, rozsah1, value), Convert(rozsah1+2, rozsah, value)) :
						  (float)pow(1.0f/Convert(start+strlen(tocpy)+1, rozsah1, value), Convert(rozsah1+2, rozsah, value)*(-1.0f));
					//val = (float)pow(Convert(start+strlen(tocpy)+1, rozsah1, value), Convert(rozsah1+2, rozsah, value));
					break;
				case 4:
					rozsah = Nastav(string, start+strlen(tocpy), 41);
					val = (float)sqrt(Convert(start+strlen(tocpy)+1, rozsah, value));
					break;
				case 5:
					rozsah = Nastav(string, start+strlen(tocpy), 41);
					val = (float)lgamma(Convert(start+strlen(tocpy)+1, rozsah, value));
					break;
				case 6:
					rozsah = Nastav(string, start+strlen(tocpy), 41);
					val = (float)sin(Convert(start+strlen(tocpy)+1, rozsah, value));
					break;
				case 7:
					rozsah = Nastav(string, start+strlen(tocpy), 41);
					val = (float)tgamma(Convert(start+strlen(tocpy)+1, rozsah, value));
					break;
				case 8:
					rozsah = Nastav(string, start+strlen(tocpy), 41);
					if (Convert(start+strlen(tocpy)+1, rozsah, value) < 0.0f) val = Convert(start+strlen(tocpy)+1, rozsah, value) * -1.0f;
					else val = Convert(start+strlen(tocpy)+1, rozsah, value);
					break;
				case 9:
					rozsah = Nastav(string, start+strlen(tocpy), 41);
					val = (float)atan(Convert(start+strlen(tocpy)+1, rozsah, value));
					break;
				case 10:
					rozsah = Nastav(string, start+strlen(tocpy), 41);
					val = (float)tanh(Convert(start+strlen(tocpy)+1, rozsah, value));
					break;
				case 11:
					rozsah = Nastav(string, start+strlen(tocpy), 41);
					val = (float)ceil(Convert(start+strlen(tocpy)+1, rozsah, value));
					break;
				case 12:
					rozsah = Nastav(string, start+strlen(tocpy), 41);
					val = (float)cbrt(Convert(start+strlen(tocpy)+1, rozsah, value));
					break;
				case 13:
					rozsah = Nastav(string, start+strlen(tocpy), 41);
					val = (float)floor(Convert(start+strlen(tocpy)+1, rozsah, value));
					break;
				case 14:
					rozsah = Nastav(string, start+strlen(tocpy), 41);
					val = (float)acos(Convert(start+strlen(tocpy)+1, rozsah, value));
					break;
				case 15:
					rozsah = Nastav(string, start+strlen(tocpy), 41);
					val = (float)asin(Convert(start+strlen(tocpy)+1, rozsah, value));
					break;
				case 16:
					rozsah = Nastav(string, start+strlen(tocpy), 41);
					val = (float)cosh(Convert(start+strlen(tocpy)+1, rozsah, value));
					break;
				case 17:
					rozsah = Nastav(string, start+strlen(tocpy), 41);
					val = (float)sinh(Convert(start+strlen(tocpy)+1, rozsah, value));
					break;
				case 18:
					rozsah = Nastav(string, start+strlen(tocpy), 41);
					val = (float)expl(Convert(start+strlen(tocpy)+1, rozsah, value));
					break;
				case 19:
					rozsah = Nastav(string, start+strlen(tocpy), 41);
					val = (float)log10(Convert(start+strlen(tocpy)+1, rozsah, value));
					break;
				case 20:
					val = infinity();
					break;
				case 21:
					rozsah = Nastav(string, start+strlen(tocpy), 41);
					val = (float)asinh(Convert(start+strlen(tocpy)+1, rozsah, value));
					break;
				case 22:
					rozsah = Nastav(string, start+strlen(tocpy), 41);
					val = (float)acosh(Convert(start+strlen(tocpy)+1, rozsah, value));
					break;
				case 23:
					rozsah = Nastav(string, start+strlen(tocpy), 41);
					val = (float)atanh(Convert(start+strlen(tocpy)+1, rozsah, value));
					break;
				case 24:
					rozsah = Nastav(string, start+strlen(tocpy), 41);
					val = 1.0f / (float)tan(Convert(start+strlen(tocpy)+1, rozsah, value));
					break;
				case 25:
					rozsah = Nastav(string, start+strlen(tocpy), 41);
					val = (float)trunc(Convert(start+strlen(tocpy)+1, rozsah, value));
					break;
					//"atan", "tanh", "ceil", "cbrt", "floor", "acos", "asin", "cosh", "sinh",
				    //"expl", "log10", "infinity", "asinh", "acosh", "atanh", "cotg", "trunc" };
			}
			return val;
		}
	}

	if (strcmp(extto, "x") == 0) {
		val = value;
		return val;
	}
	if (strcmp(extto, "pi") == 0) {
		val = M_PI;
		return val;
	}
	if (strcmp(extto, "e") == 0) {
		val = M_E;
		return val;
	}
	if ((strcmp(extto, "y") == 0) && (yorz == 2)) {
		val = y;
		return val;
	}
	BOOL isnumber = TRUE;
	if (strcmp(extto, "") != 0) {
		for (int u = 0; u < strlen(extto); u++) {
			if ((extto[u] < 45) || (extto[u] > 46)) {
				if ((extto[u] > 57) || (extto[u] < 48)) {
					isnumber = FALSE;
				}
			}
		}
	}
	if (isnumber == TRUE) {
		val = atof(extto);
		return val;
	}

	val = 0.0f;
	return val;
}

void ExtractStrings() {
	int s = 0;
	int t = 0;
	int corr = 0;
	char ttext[1000];
	memset(etext, 0, sizeof(etext));
	memset(etext2, 0, sizeof(etext2));
	SendMessage(hEdit, WM_GETTEXT, 990, (LPARAM)ttext);
	while ((ttext[s] != 121) || (ttext[s+1] != 61)) {
		s++;
		if (s == 992) {
			strcpy(etext, "y=0;");
			break;
		}
	}
	if (s != 992) {
		while (ttext[s+t-1] != 59) {
			if ((ttext[s+t] >= 1) && (ttext[s+t] <= 32)) {
				corr++;
			} else {
				etext[t-corr] = ttext[s+t];
			}
			t++;
		}
	}
	s = 0;
	t = 0;
	corr = 0;
	while ((ttext[s] != 122) || (ttext[s+1] != 61)) {
		s++;
		if (s == 992) {
			strcpy(etext2, "z=0;");
			break;
		}
	}
	if (s != 992) {
		while (ttext[s+t-1] != 59) {
			if ((ttext[s+t] >= 1) && (ttext[s+t] <= 32)) {
				corr++;
			} else {
				etext2[t-corr] = ttext[s+t];
			}

			if (t == 992) break;
			t++;
		}
	}
}

int ControlExeChange() {
	if (strcmp(autor, "Grafy funkcí - Jakub Vonšovský - 2006") != 0) return 1;
	if (strlen(autor) != 37) return 1;
	if (autor[21] != 86) return 1;
	if (autor[28] != 107) return 1;
	return 0;
}

void NaplnList() {
	int firstload = 1;
	if (zapis == TRUE) {
		char znak[130];
		while (fgets(znak, 128, fhin) != NULL) {
			if (znak[strlen(znak)-1] == 10)
				znak[strlen(znak)-1] = 0;
			if ((znak[0] == 122) && (znak[1] == 61) || (znak[0] == 121) && (znak[1] == 61))
				SendMessage(hList, LB_ADDSTRING, 0, (LPARAM)znak);
			firstload = 0;
		}
		if (firstload == 1) {
			if (zapis == TRUE) {
				fputs("y=x;", fhin); fputs("\n", fhin);
				fputs("y=pi;", fhin); fputs("\n", fhin);
				fputs("y=e;", fhin); fputs("\n", fhin);
				fputs("y=abs(x);", fhin); fputs("\n", fhin);
				fputs("y=1/x;", fhin); fputs("\n", fhin);
				fputs("y=pow(x, 2);", fhin); fputs("\n", fhin);
				fputs("y=sqrt(x);", fhin); fputs("\n", fhin);
				fputs("y=sin(x);", fhin); fputs("\n", fhin);
				fputs("y=cos(x);", fhin); fputs("\n", fhin);
				fputs("y=tan(x);", fhin); fputs("\n", fhin);
				fputs("y=cotg(x);", fhin); fputs("\n", fhin);
				fputs("y=log(x);", fhin); fputs("\n", fhin);
				fputs("y=int(x);", fhin); fputs("\n", fhin);
				fputs("y=lgamma(x);", fhin); fputs("\n", fhin);
			}

		}
	} if ((zapis == FALSE) || (firstload == 1)) {
		SendMessage(hList, LB_ADDSTRING, 0, (LPARAM)"y=x;");
		SendMessage(hList, LB_ADDSTRING, 0, (LPARAM)"y=pi;  //èíslo pí");
		SendMessage(hList, LB_ADDSTRING, 0, (LPARAM)"y=e;  //eulerovo èíslo");
		SendMessage(hList, LB_ADDSTRING, 0, (LPARAM)"y=abs(x);  //absolutní hodnota z x");
		SendMessage(hList, LB_ADDSTRING, 0, (LPARAM)"y=1/x;");
		SendMessage(hList, LB_ADDSTRING, 0, (LPARAM)"y=pow(x, 2);  //x na druhou");
		SendMessage(hList, LB_ADDSTRING, 0, (LPARAM)"y=sqrt(x);  //odmocnina z x");
		SendMessage(hList, LB_ADDSTRING, 0, (LPARAM)"y=sin(x);  //sinus x");
		SendMessage(hList, LB_ADDSTRING, 0, (LPARAM)"y=cos(x);  //cosinus x");
		SendMessage(hList, LB_ADDSTRING, 0, (LPARAM)"y=tan(x);  //tangens x");
		SendMessage(hList, LB_ADDSTRING, 0, (LPARAM)"y=cotg(x); //cotangens x");
		SendMessage(hList, LB_ADDSTRING, 0, (LPARAM)"y=log(x);  //logaritmus x");
		SendMessage(hList, LB_ADDSTRING, 0, (LPARAM)"y=int(x);  //celá èást èísla x");
		SendMessage(hList, LB_ADDSTRING, 0, (LPARAM)"y=lgamma(x);  //log gamma x");
	}
}

void PrepisPredpis() {
	int l = 0;
	int k = 0;
	char listtext[100] = "";
	char edtext[1000] = "";
	char zvzorec[200] = "";
	int sel = SendMessage(hList, LB_GETCURSEL, 0, 0);
	SendMessage(hList, LB_GETTEXT, sel, (LPARAM)listtext);
	//z funkce
	if ((listtext[0] == 122) && (listtext[1] == 61)) {
		SendMessage(hEdit, WM_GETTEXT, 990, (LPARAM)edtext);
		SendMessage(hEdit, WM_SETTEXT, 0, (LPARAM)"");
		for (int v = 0; v <= strlen(edtext) - 1; v++)
			if (edtext[v] == 13) edtext[v] = 0;
		char tosprint[200];
		long len = SendMessage(hEdit, EM_LINELENGTH, 0, 0);
		SendMessage(hEdit, EM_SETSEL, len, len);
		strcat(edtext, "\r\n");
		strcat(edtext, listtext);
		SendMessage(hEdit, EM_REPLACESEL, 20, (LPARAM)edtext);
	}
	//y funkce
	if ((listtext[0] == 121) && (listtext[1] == 61)) {
		SendMessage(hEdit, WM_GETTEXT, 990, (LPARAM)edtext);
		SendMessage(hEdit, WM_SETTEXT, 0, (LPARAM)"");
		while (edtext[l] != 13) {
			if (l == strlen(edtext)) break;
			l++;
		}
		while (l != strlen(edtext)) {
			zvzorec[k] = edtext[l];
			l++; k++;
		}
		char doeditu[200] = "";
		strcpy(doeditu, listtext);
		strcat(doeditu, zvzorec);
		SendMessage(hEdit, WM_SETTEXT, 1, (LPARAM)doeditu);
	}
	SetFocus(hwnD);
}

//v listboxu vstup z klávesnice

void SaveActive() {
	FILE *fact;
	char Etext[1000] = "";
	if ((fact = fopen("settings.txt", "w")) != NULL) {
		SendMessage(hEdit, WM_GETTEXT, 990, (LPARAM)Etext);
		fputs(Etext, fact);
		fputs("\n@@@\n", fact);
		fputs("fullscreen=", fact);
		if (fullscreen == FALSE) strcpy(Etext, "0;");
		else strcpy(Etext, "0;");
		fputs(Etext, fact);
		fclose(fact);
	}
}

void LoadActive() {
	FILE *fact;
	char Etext[1000] = "";
	memset(Espr, 0, sizeof(Espr));
	int mode = -1;
	if ((fact = fopen("settings.txt", "r")) != NULL) {
		while (fgets(Etext, 128, fact) != NULL) {
			for (int w = 0; w < strlen(Etext); w++) {
				if (Etext[w] == 59) mode++;
				if (Etext[0] == 64) {
					fgets(Etext, 128, fact);
					mode = 2;
					break;
				}
			}
			switch(mode) {
				case 0:
					if ((Etext[strlen(Etext) - 1] == 10) && (Etext[strlen(Etext) - 2] != 13))
						Etext[strlen(Etext) - 1] = 0;
					strcat(Espr, Etext);
					break;
				case 1:
					if ((Etext[strlen(Etext) - 1] == 10) && (Etext[strlen(Etext) - 2] != 13))
						Etext[strlen(Etext) - 1] = 0;
					strcat(Espr, Etext);
					break;
				case 2:
					char fullsc[3] = "";
					fullsc[0] = Etext[strlen(Etext) - 2];
					fullscreen = atoi(fullsc);
					break;
			}
		}
	}
}

int CheckErrors() {
	if ((GetFocus() != hwnD) && (GetFocus() != Hwnd)) {
		char gtext[1000];
		SendMessage(hEdit, WM_GETTEXT, 990, (LPARAM)gtext);
		int stredniky = 0;
		int carky = 0;
		int zavorky = 0;
		for (int x = 0; x < strlen(gtext); x++) {
			if ((gtext[x] == 121) && (gtext[x+1] == 61)) stredniky++;
			if ((gtext[x] == 122) && (gtext[x+1] == 61)) stredniky++;
			if (gtext[x] == 59) stredniky--;
			if ((gtext[x] == 112) && (gtext[x+1] == 111) && (gtext[x+2] == 119)) carky++;
			if (gtext[x] == 44) carky--;
			if (gtext[x] == 40) zavorky++;
			if (gtext[x] == 41) zavorky--;
		}
		if (stredniky != 0) {
			MessageBox(NULL, "Zkontrolujte støedníky", "Chyba", 0 + MB_OK + MB_TASKMODAL + MB_ICONERROR);
			SetFocus(Hwnd);
			return 1;
		}
		if (carky != 0) {
			MessageBox(Hwnd, "Zkontrolujte umocòování", "Chyba", 0 + MB_OK + MB_TASKMODAL + MB_ICONERROR);
			SetFocus(Hwnd);
			return 1;
		}
		if (zavorky != 0) {
			MessageBox(Hwnd, "Zkontrolujte závorky", "Chyba", 0 + MB_OK + MB_TASKMODAL + MB_ICONERROR);
			SetFocus(Hwnd);
			return 1;
		}
	}
	return 0;
}

int Nastav(char *strchar, int ab, int asc) {
	int set = 1;
	while (set != 0) {
		ab++;
		if ((strchar[ab] == 40) && (asc == 41)) set++;
		if ((strchar[ab] == 41) && (asc == 41)) set--;
		if ((strchar[ab] == 112) && (strchar[ab] == 111) && (strchar[ab] == 119) && (asc == 44)) set++;
		if ((strchar[ab] == 44) && (asc == 44)) set--;
	}
	return ab-1;
}
