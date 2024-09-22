import sys
import inspect
import evote_face_recognition

try:
	print("face_recognition version:")
	print(evote_face_recognition.__version__)
except Exception as e:
	print(e)

try:
	print()
	print("face_recognition path:")
	print(evote_face_recognition.__file__)
except Exception as e:
	print(e)


try:
	print()
	print("face_recognition path (alternate method):")
	print(inspect.getfile(evote_face_recognition))
except Exception as e:
	print(e)

print()
print("Python version:")
print(sys.version)
print("Python executable:")
print(sys.executable)

try: 
	print("Python base path:")
	print(sys.base_prefix)
except Exception as e:
	print(e)

print("Python base path (exec):")
print(sys.exec_prefix)
print("Python system path:")
print(sys.path)